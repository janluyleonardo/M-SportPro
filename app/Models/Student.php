<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClub;

class Student extends Model
{
  use HasFactory, BelongsToClub;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'club_id',
    'Photo',
    'Categoria',
    'fechaInscripcion',
    'nomDeportista',
    'numDocumento',
    'genero',
    'PesoDeportista',
    'EstaturaDeportista',
    'RHDeportista',
    'fechaNacimiento',
    'Ciudad',
    'Departamento',
    'EPS',
    'Colegio',
    'Curso',
    'numTelefonico',
    'numTelefonicoUno',
    'numTelefonicoDos',
    'direccionDeportista',
    'barrio',
    'localidad',
    'nombreMama',
    'documentoMama',
    'telefonoMama',
    'direccionMama',
    'correoMama',
    'nombrePapa',
    'documentoPapa',
    'telefonoPapa',
    'direccionPapa',
    'correoPapa',
    'enfermedades',
    'medicamento',
    'lesion',
    'Cirugia',
    'impedimento',
    'lesionOM',
    'balance',
  ];

  public function payments()
  {
      return $this->hasMany(Payment::class);
  }

  public function attendances()
  {
      return $this->hasMany(Attendance::class);
  }

  public function tournaments()
  {
      return $this->belongsToMany(Tournament::class, 'student_tournament');
  }

  /**
   * Calcula la deuda total del estudiante mes a mes con carry-forward.
   * El excedente de un mes (pago de más) se arrastra como crédito al mes siguiente.
   */
  public function calculateDebt()
  {
      if (!$this->fechaInscripcion) return 0;

      $startDate = \Carbon\Carbon::parse($this->fechaInscripcion)->startOfMonth();
      $endDate   = now()->startOfMonth();

      $dayThreshold  = config('app.payment_late_day_threshold', 10);
      $lateFeeAmount = config('app.payment_late_fee_amount', 5000);
      $baseAmount    = config('app.default_payment_amount', 50000);

      // Agrupar pagos por año-mes: suma de montos, si alguno tiene waive y fecha último pago
      $paidByMonth = $this->payments()
          ->where('status', 'paid')
          ->get()
          ->groupBy(fn($p) => $p->year . '-' . $p->month)
          ->map(fn($group) => [
              'total'          => $group->sum('amount'),
              'waive_late_fee' => $group->contains('waive_late_fee', true),
          ]);

      $totalDebt   = 0;
      $carry       = 0; // saldo a favor acumulado
      $currentDate = $startDate->copy();

      while ($currentDate <= $endDate) {
          $amountDue = $baseAmount;
          $isLate    = ($currentDate < $endDate) ||
                       ($currentDate->isSameMonth(now()) && now()->day > $dayThreshold);

          if ($isLate) {
              $amountDue += $lateFeeAmount; // recargo fijo en moneda local
          }

          $key        = $currentDate->year . '-' . $currentDate->month;
          $monthData  = $paidByMonth[$key] ?? null;
          $paid       = ($monthData ? $monthData['total'] : 0) + $carry;

          // Si el pago tiene exoneración, el umbral es el monto base (sin recargo)
          $threshold  = ($monthData && $monthData['waive_late_fee']) ? $baseAmount : $amountDue;
          $carry      = max(0, $paid - $threshold);
          $pending    = max(0, $threshold - $paid);
          $totalDebt += $pending;

          $currentDate->addMonth();
      }

      return $totalDebt;
  }

  public function updateBalance()
  {
      $this->balance = $this->calculateDebt();
      $this->save();
      $this->syncAttendanceSlots();
      return $this->balance;
  }

  /**
   * Obtiene el listado de estados por mes con carry-forward (saldo a favor).
   * El excedente de un mes se arrastra automáticamente al mes siguiente como crédito.
   * Expone carry_used y surplus_generated para mostrar en la vista.
   */
  public function getPaymentStatusByMonth()
  {
      if (!$this->fechaInscripcion) return [];

      $startDate = \Carbon\Carbon::parse($this->fechaInscripcion)->startOfMonth();
      $endDate   = now()->startOfMonth();

      $dayThreshold  = config('app.payment_late_day_threshold', 10);
      $lateFeeAmount = config('app.payment_late_fee_amount', 5000);
      $baseAmount    = config('app.default_payment_amount', 50000);

      // Agrupar pagos por año-mes: suma de montos, waive y fecha del último pago
      $allPayments = $this->payments()->where('status', 'paid')->get();

      $paidByMonth = $allPayments
          ->groupBy(fn($p) => $p->year . '-' . $p->month)
          ->map(fn($group) => [
              'total'          => $group->sum('amount'),
              'paid_at'        => $group->sortByDesc('paid_at')->first()->paid_at,
              'waive_late_fee' => $group->contains('waive_late_fee', true),
          ]);

      $statusList  = [];
      $carry       = 0; // saldo a favor acumulado
      $currentDate = $startDate->copy();
      $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

      while ($currentDate <= $endDate) {
          $amountDue = $baseAmount;
          $isLate    = ($currentDate < $endDate) ||
                       ($currentDate->isSameMonth(now()) && now()->day > $dayThreshold);

          if ($isLate) {
              $amountDue += $lateFeeAmount; // recargo fijo en moneda local
          }

          $key           = $currentDate->year . '-' . $currentDate->month;
          $monthData     = $paidByMonth[$key] ?? null;
          $paidThisMonth = $monthData ? $monthData['total'] : 0;
          $waived        = $monthData && $monthData['waive_late_fee'];

          // Si hay exoneración, el umbral para considerar saldado es el monto base
          $threshold         = $waived ? $baseAmount : $amountDue;

          // Disponible = pago real de este mes + crédito arrastrado del anterior
          $available         = $paidThisMonth + $carry;
          $covered           = min($available, $threshold);
          $isPaid            = $available >= $threshold;
          $surplusGenerated  = max(0, $available - $threshold);
          $carryUsed         = min($carry, $covered);
          $carry             = $surplusGenerated;

          $statusList[] = [
              'month_name'        => $meses[$currentDate->month - 1],
              'year'              => $currentDate->year,
              'month_num'         => $currentDate->month,
              'is_paid'           => $isPaid,
              'amount'            => $amountDue,
              'threshold'         => $threshold,
              'covered'           => $covered,
              'pending'           => max(0, $threshold - $available),
              'is_late'           => $isLate,
              'paid_at'           => $monthData ? $monthData['paid_at'] : null,
              'carry_used'        => $carryUsed,
              'surplus_generated' => $surplusGenerated,
              'waive_late_fee'    => $waived,
          ];

          $currentDate->addMonth();
      }

      return array_reverse($statusList);
  }

    public function attendanceSlots()
    {
        return $this->hasMany(AttendanceSlot::class);
    }

    public function syncAttendanceSlots()
    {
        $statuses = $this->getPaymentStatusByMonth();
        
        foreach ($statuses as $status) {
            if ($status['is_paid']) {
                AttendanceSlot::firstOrCreate(
                    [
                        'student_id' => $this->id,
                        'month' => $status['month_num'],
                        'year' => $status['year']
                    ],
                    [
                        'classes_used' => 0,
                        'classes_allowed' => 8
                    ]
                );
            }
        }
    }
}

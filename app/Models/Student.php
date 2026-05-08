<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
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

  /**
   * Calcula la deuda total acumulada del estudiante como una cuenta corriente.
   * Total de cargos requeridos - Total de abonos realizados.
   */
  public function calculateDebt()
  {
      if (!$this->fechaInscripcion) return 0;

      $startDate = \Carbon\Carbon::parse($this->fechaInscripcion)->startOfMonth();
      $endDate = now()->startOfMonth();
      
      $dayThreshold = config('app.payment_late_day_threshold', 10);
      $feePercentage = config('app.payment_late_fee_percentage', 10);
      $baseAmount = config('app.default_payment_amount', 50000);

      $totalRequired = 0;
      $currentDate = $startDate->copy();

      // 1. Calcular todo lo que el estudiante DEBERÍA haber pagado hasta hoy
      while ($currentDate <= $endDate) {
          $amount = $baseAmount;
          $isLate = false;

          if ($currentDate < $endDate) {
              // Mes pasado y no se pagó en su momento (o ya pasó)
              $isLate = true;
          } elseif ($currentDate->isSameMonth(now()) && now()->day > $dayThreshold) {
              // Mes actual y ya pasó el día límite
              $isLate = true;
          }

          if ($isLate) {
              $amount += ($amount * ($feePercentage / 100));
          }

          $totalRequired += $amount;
          $currentDate->addMonth();
      }

      // 2. Calcular todo lo que el estudiante HA PAGADO en su historia
      $totalPaid = $this->payments()->where('status', 'paid')->sum('amount');

      // 3. El saldo es la diferencia
      return $totalRequired - $totalPaid;
  }

  public function updateBalance()
  {
      $this->balance = $this->calculateDebt();
      $this->save();
      $this->syncAttendanceSlots();
      return $this->balance;
  }

  /**
   * Obtiene el listado de estados por mes repartiendo el dinero total pagado
   * de forma cronológica (FIFO - Primero en entrar, primero en salir).
   */
  public function getPaymentStatusByMonth()
  {
      if (!$this->fechaInscripcion) return [];

      $startDate = \Carbon\Carbon::parse($this->fechaInscripcion)->startOfMonth();
      $endDate = now()->startOfMonth();
      
      $dayThreshold = config('app.payment_late_day_threshold', 10);
      $feePercentage = config('app.payment_late_fee_percentage', 10);
      $baseAmount = config('app.default_payment_amount', 50000);

      // Total de dinero disponible del estudiante
      $remainingPaid = $this->payments()->where('status', 'paid')->sum('amount');

      $statusList = [];
      $currentDate = $startDate->copy();
      $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

      // Calculamos mes a mes desde el principio para repartir el dinero
      $months = [];
      while ($currentDate <= $endDate) {
          $months[] = $currentDate->copy();
          $currentDate->addMonth();
      }

      foreach ($months as $date) {
          $amountDue = $baseAmount;
          $isLate = false;

          if ($date < $endDate) {
              $isLate = true;
          } elseif ($date->isSameMonth(now()) && now()->day > $dayThreshold) {
              $isLate = true;
          }

          if ($isLate) {
              $amountDue += ($amountDue * ($feePercentage / 100));
          }

          // Repartir el dinero disponible a este mes
          $covered = min($remainingPaid, $amountDue);
          $remainingPaid -= $covered;

          $statusList[] = [
              'month_name' => $meses[$date->month - 1],
              'year' => $date->year,
              'month_num' => $date->month,
              'is_paid' => $covered >= $amountDue,
              'amount' => $amountDue,
              'covered' => $covered,
              'pending' => $amountDue - $covered,
              'is_late' => $isLate,
              // Para compatibilidad con la vista
              'paid_at' => ($covered >= $amountDue) ? now() : null 
          ];
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

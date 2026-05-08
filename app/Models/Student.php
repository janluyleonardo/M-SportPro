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
   * Calcula la deuda total acumulada del estudiante.
   * Basado en meses pendientes desde la fecha de inscripción.
   */
  public function calculateDebt()
  {
      if (!$this->fechaInscripcion) return 0;

      $startDate = \Carbon\Carbon::parse($this->fechaInscripcion)->startOfMonth();
      $endDate = now()->startOfMonth();
      
      $dayThreshold = config('app.payment_late_day_threshold', 10);
      $feePercentage = config('app.payment_late_fee_percentage', 10);
      $baseAmount = config('app.default_payment_amount', 50000);

      $totalDebt = 0;
      $currentDate = $startDate->copy();

      // Obtener meses ya pagados
      $paidMonths = $this->payments()->where('status', 'paid')
          ->get()
          ->map(fn($p) => $p->year . '-' . str_pad($p->month, 2, '0', STR_PAD_LEFT))
          ->toArray();

      while ($currentDate <= $endDate) {
          $monthKey = $currentDate->format('Y-m');
          
          if (!in_array($monthKey, $paidMonths)) {
              $amount = $baseAmount;
              $isLate = false;

              if ($currentDate < $endDate) {
                  // Mes pasado y no pagado
                  $isLate = true;
              } elseif ($currentDate->isSameMonth(now()) && now()->day > $dayThreshold) {
                  // Mes actual y ya pasó el día límite
                  $isLate = true;
              }

              if ($isLate) {
                  $amount += ($amount * ($feePercentage / 100));
              }

              $totalDebt += $amount;
          }
          $currentDate->addMonth();
      }

      return $totalDebt;
  }

  public function updateBalance()
  {
      $this->balance = $this->calculateDebt();
      $this->save();
      return $this->balance;
  }

  /**
   * Obtiene el listado detallado de estados por mes (Pagado / Pendiente)
   */
  public function getPaymentStatusByMonth()
  {
      if (!$this->fechaInscripcion) return [];

      $startDate = \Carbon\Carbon::parse($this->fechaInscripcion)->startOfMonth();
      $endDate = now()->startOfMonth();
      
      $dayThreshold = config('app.payment_late_day_threshold', 10);
      $feePercentage = config('app.payment_late_fee_percentage', 10);
      $baseAmount = config('app.default_payment_amount', 50000);

      $statusList = [];
      $currentDate = $startDate->copy();

      // Obtener pagos realizados
      $payments = $this->payments()->where('status', 'paid')->get()
          ->keyBy(fn($p) => $p->year . '-' . str_pad($p->month, 2, '0', STR_PAD_LEFT));

      $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

      // Iteramos desde el final hacia el principio para mostrar primero lo más reciente
      $monthsToCalculate = [];
      while ($currentDate <= $endDate) {
          $monthsToCalculate[] = $currentDate->copy();
          $currentDate->addMonth();
      }

      foreach (array_reverse($monthsToCalculate) as $date) {
          $monthKey = $date->format('Y-m');
          $payment = $payments->get($monthKey);
          
          $isPaid = (bool)$payment;
          $amount = $baseAmount;
          $isLate = false;

          if (!$isPaid) {
              if ($date < $endDate) {
                  $isLate = true;
              } elseif ($date->isSameMonth(now()) && now()->day > $dayThreshold) {
                  $isLate = true;
              }

              if ($isLate) {
                  $amount += ($amount * ($feePercentage / 100));
              }
          } else {
              $amount = $payment->amount;
          }

          $statusList[] = [
              'month_name' => $meses[$date->month - 1],
              'year' => $date->year,
              'month_num' => $date->month,
              'is_paid' => $isPaid,
              'amount' => $amount,
              'is_late' => $isLate,
              'paid_at' => $isPaid ? $payment->paid_at : null
          ];
      }

      return $statusList;
  }
}

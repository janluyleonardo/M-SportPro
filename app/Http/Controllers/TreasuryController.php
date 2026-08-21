<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TreasuryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $query = Transaction::query();

        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        $transactions = $query->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->latest('id') // Ordenar por ID descendente para ver lo último primero
            ->paginate(10); // Paginación corta de 10 registros

        $totalIncome = Transaction::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = Transaction::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('type', 'expense')
            ->sum('amount');

        $products = \App\Models\Product::orderBy('name')->get();
        $teachers = User::role('Profesor')->get();
        $invoiceSettings = \App\Models\InvoiceSetting::firstOrCreate([], [
            'prefix' => 'JFS-',
            'next_number' => 1001
        ]);

        return view('treasury.index', compact('transactions', 'totalIncome', 'totalExpense', 'month', 'year', 'products', 'teachers', 'invoiceSettings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'prefix' => 'required|string|max:10',
            'next_number' => 'required|integer|min:1',
            'resolution_number' => 'nullable|string'
        ]);

        $settings = \App\Models\InvoiceSetting::first();
        if (!$settings) $settings = new \App\Models\InvoiceSetting();

        $settings->fill($request->all());
        $settings->save();

        return back()->with('success', 'Configuración de facturación actualizada.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'custom_category' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Generar número de factura automático si es un INGRESO
        if ($validated['type'] == 'income') {
            $validated['invoice_number'] = \App\Models\InvoiceSetting::generateNext();
        }

        $transaction = Transaction::create($validated);

        // Si es venta de artículos y hay un producto seleccionado, descontar stock (Venta)
        if ($validated['type'] == 'income' && $validated['category'] == 'sporting_goods' && !empty($validated['product_id'])) {
            $product = \App\Models\Product::find($validated['product_id']);
            if ($product) {
                $qty = $validated['quantity'] ?? 1;
                $product->decrement('stock', $qty);
            }
        }

        // Si es compra de artículos y hay un producto seleccionado, aumentar stock (Reabastecimiento)
        if ($validated['type'] == 'expense' && $validated['category'] == 'sporting_goods' && !empty($validated['product_id'])) {
            $product = \App\Models\Product::find($validated['product_id']);
            if ($product) {
                $qty = $validated['quantity'] ?? 1;
                $product->increment('stock', $qty);
            }
        }

        return back()->with('success', 'Transacción registrada correctamente.');
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'custom_category' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($transaction, $validated) {
            $this->adjustProductStock($transaction->type, $transaction->category, $transaction->product_id, $transaction->quantity, true);
            $transaction->update($validated);
            $this->adjustProductStock($validated['type'], $validated['category'], $validated['product_id'] ?? null, $validated['quantity'] ?? null);
        });

        return back()->with('success', 'Transacción actualizada correctamente.');
    }

    private function adjustProductStock(string $type, string $category, ?int $productId, ?int $quantity, bool $reverse = false): void
    {
        if ($category !== 'sporting_goods' || !$productId) {
            return;
        }

        $product = \App\Models\Product::find($productId);
        if (!$product) {
            return;
        }

        $quantity = $quantity ?: 1;
        $isSale = $type === 'income';
        $shouldIncrement = $reverse ? $isSale : !$isSale;

        if ($shouldIncrement) {
            $product->increment('stock', $quantity);
        } else {
            $product->decrement('stock', $quantity);
        }
    }

    public function salaries(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $teachers = User::role('Profesor')->get();

        $salaryData = $teachers->map(function($teacher) use ($month, $year) {
            // Calcular sesiones únicas
            $sessions = DB::table('attendances')
                ->join('class_schedules', 'attendances.class_schedule_id', '=', 'class_schedules.id')
                ->where('class_schedules.user_id', $teacher->id)
                ->whereMonth('attendances.date', $month)
                ->whereYear('attendances.date', $year)
                ->select('attendances.date', 'attendances.class_schedule_id')
                ->distinct()
                ->get();

            $sessionsCount = $sessions->count();

            $payRate = $teacher->pay_per_session > 0 ? $teacher->pay_per_session : config('app.default_teacher_pay_per_session', 30000);
            $totalEarned = $sessionsCount * $payRate;

            $paid = Transaction::where('user_id', $teacher->id)
                ->where('category', 'teacher_salary')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            // Calcular préstamos y abonos
            $totalLoans = Transaction::where('user_id', $teacher->id)
                ->where('category', 'teacher_loan')
                ->sum('amount');

            $totalRepayments = Transaction::where('user_id', $teacher->id)
                ->where('category', 'loan_repayment')
                ->sum('amount');

            $pendingLoan = max(0, $totalLoans - $totalRepayments);

            return [
                'teacher' => $teacher,
                'sessions_count' => $sessionsCount,
                'total_earned' => $totalEarned,
                'paid' => $paid,
                'pending' => $totalEarned - $paid,
                'pending_loan' => $pendingLoan
            ];
        });

        return view('treasury.salaries', compact('salaryData', 'month', 'year'));
    }

    public function payTeacher(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'loan_deduction' => 'nullable|numeric|min:0',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $teacher = User::findOrFail($request->user_id);

        $sessions = DB::table('attendances')
            ->join('class_schedules', 'attendances.class_schedule_id', '=', 'class_schedules.id')
            ->where('class_schedules.user_id', $teacher->id)
            ->whereMonth('attendances.date', $request->month)
            ->whereYear('attendances.date', $request->year)
            ->select('attendances.date', 'attendances.class_schedule_id')
            ->distinct()
            ->get();

        $sessionsCount = $sessions->count();
        $payRate = $teacher->pay_per_session > 0 ? $teacher->pay_per_session : config('app.default_teacher_pay_per_session', 30000);
        $totalEarned = $sessionsCount * $payRate;

        $paid = Transaction::where('user_id', $teacher->id)
            ->where('category', 'teacher_salary')
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->sum('amount');

        $pending = $totalEarned - $paid;

        if ($pending <= 0) {
            return back()->with('error', 'El profesor ya no tiene saldo pendiente por pagar para este mes.');
        }

        $deduction = (float)($request->loan_deduction ?? 0);
        if ($deduction > $pending) {
            $deduction = $pending;
        }

        $netToPay = $pending - $deduction;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('vouchers/payroll', $fileName, 'public');
        }

        DB::transaction(function() use ($request, $netToPay, $deduction, $attachmentPath) {
            // 1. Registrar el egreso neto de nómina
            if ($netToPay > 0) {
                Transaction::create([
                    'type' => 'expense',
                    'category' => 'teacher_salary',
                    'amount' => $netToPay,
                    'date' => now()->format('Y-m-d'),
                    'description' => "Pago de nómina mes {$request->month}/{$request->year}" . ($deduction > 0 ? " (Descuento de préstamo: $$deduction)" : ""),
                    'user_id' => $request->user_id,
                    'attachment' => $attachmentPath,
                ]);
            }

            // 2. Registrar el abono al préstamo (Ingreso contable de balance)
            if ($deduction > 0) {
                Transaction::create([
                    'type' => 'income',
                    'category' => 'loan_repayment',
                    'amount' => $deduction,
                    'date' => now()->format('Y-m-d'),
                    'description' => "Abono préstamo por descuento en nómina mes {$request->month}/{$request->year}",
                    'user_id' => $request->user_id,
                ]);
            }
        });

        return back()->with('success', 'Pago de nómina registrado con soporte.');
    }

    public function teacherHistory(User $teacher)
    {
        $payments = Transaction::where('user_id', $teacher->id)
            ->where('category', 'teacher_salary')
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('treasury.teacher_history', compact('teacher', 'payments'));
    }
}

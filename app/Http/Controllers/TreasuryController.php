<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Payment;
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
        $invoiceSettings = \App\Models\InvoiceSetting::firstOrCreate([], [
            'prefix' => 'JFS-',
            'next_number' => 1001
        ]);

        return view('treasury.index', compact('transactions', 'totalIncome', 'totalExpense', 'month', 'year', 'products', 'invoiceSettings'));
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

            return [
                'teacher' => $teacher,
                'sessions_count' => $sessionsCount,
                'total_earned' => $totalEarned,
                'paid' => $paid,
                'pending' => $totalEarned - $paid
            ];
        });

        return view('treasury.salaries', compact('salaryData', 'month', 'year'));
    }

    public function payTeacher(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('vouchers/payroll', $fileName, 'public');
        }

        Transaction::create([
            'type' => 'expense',
            'category' => 'teacher_salary',
            'amount' => $request->amount,
            'date' => now()->format('Y-m-d'),
            'description' => "Pago de nómina mes {$request->month}/{$request->year}",
            'user_id' => $request->user_id,
            'attachment' => $attachmentPath,
        ]);

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

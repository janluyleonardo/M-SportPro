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
            ->orderBy('date', 'desc')
            ->paginate(15);

        $totalIncome = Transaction::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = Transaction::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('type', 'expense')
            ->sum('amount');

        return view('treasury.index', compact('transactions', 'totalIncome', 'totalExpense', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Transaction::create($validated);

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

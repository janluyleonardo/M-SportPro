<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-club-secondary/20 text-club-primary rounded-2xl">
                    <i class="bi bi-people-fill text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-gray-900 tracking-tight">
                        {{ __('Payroll Calculation') }}
                    </h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Teacher Settlement by Session') }}</p>
                </div>
            </div>
            
            <form action="{{ route('treasury.salaries') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
                <select name="month" class="border-none focus:ring-0 text-xs font-bold text-gray-600 bg-transparent py-1">
                    @php $meses = [__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')]; @endphp
                    @foreach($meses as $idx => $m)
                        <option value="{{ $idx + 1 }}" {{ $month == ($idx + 1) ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
                <select name="year" class="border-none focus:ring-0 text-xs font-bold text-gray-600 bg-transparent py-1">
                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="p-1.5 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="bi bi-filter text-gray-400"></i>
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 mb-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-100">
                <div class="absolute right-0 top-0 opacity-10 -mr-10 -mt-10">
                    <i class="bi bi-cash-stack text-[12rem]"></i>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-3xl font-black mb-2 uppercase tracking-tight">{{ __('Monthly Consolidated') }}</h3>
                        <p class="text-indigo-100 font-bold text-sm tracking-widest uppercase">{{ $meses[$month-1] }} {{ $year }}</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/20">
                            <p class="text-[9px] font-black uppercase text-indigo-200 tracking-widest mb-1">{{ __('Total to Liquidate') }}</p>
                            <p class="text-2xl font-black">${{ number_format($salaryData->sum('total_earned'), 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/20">
                            <p class="text-[9px] font-black uppercase text-indigo-200 tracking-widest mb-1">{{ __('Pending Payment') }}</p>
                            <p class="text-2xl font-black text-club-secondary">${{ number_format($salaryData->sum('pending'), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($salaryData as $data)
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-club-primary border border-gray-100 group-hover:bg-club-primary group-hover:text-white transition-all">
                                    <i class="bi bi-person-fill text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-lg uppercase tracking-tight">{{ $data['teacher']->name }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Teacher / Coach') }}</p>
                                        <span class="text-gray-300">•</span>
                                        <a href="{{ route('treasury.teacher_history', $data['teacher']->id) }}" class="text-[10px] font-black text-club-primary uppercase tracking-widest hover:underline">{{ __('View History') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Payment per Session') }}</p>
                                @php
                                    $effectivePay = $data['teacher']->pay_per_session > 0 ? $data['teacher']->pay_per_session : config('app.default_teacher_pay_per_session', 30000);
                                @endphp
                                <p class="font-black text-gray-900">${{ number_format($effectivePay, 0, ',', '.') }}</p>
                                @if($data['teacher']->pay_per_session <= 0)
                                    <span class="text-[8px] font-bold text-indigo-400 uppercase tracking-widest">({{ __('By Default') }})</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="bg-gray-50/50 p-4 rounded-2xl text-center border border-gray-50">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Sessions') }}</p>
                                <p class="text-lg font-black text-gray-900">{{ $data['sessions_count'] }}</p>
                            </div>
                            <div class="bg-gray-50/50 p-4 rounded-2xl text-center border border-gray-50">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Earned') }}</p>
                                <p class="text-lg font-black text-gray-900">${{ number_format($data['total_earned'], 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-indigo-50 p-4 rounded-2xl text-center border border-indigo-100">
                                <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1">{{ __('Pending') }}</p>
                                <p class="text-lg font-black text-indigo-600">${{ number_format($data['pending'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($data['pending_loan'] > 0)
                            <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl mb-6 flex justify-between items-center text-xs text-amber-800">
                                <span class="font-bold flex items-center">
                                    <i class="bi bi-exclamation-triangle-fill mr-2 text-amber-600"></i> Deuda Préstamo Pendiente:
                                </span>
                                <span class="font-black">${{ number_format($data['pending_loan'], 0, ',', '.') }}</span>
                            </div>
                        @endif

                        @if($data['pending'] > 0)
                            <form action="{{ route('treasury.pay_teacher') }}" method="POST" enctype="multipart/form-data" class="space-y-3" x-data="{ 
                                pendingSalary: {{ (float)$data['pending'] }},
                                pendingLoan: {{ (float)$data['pending_loan'] }},
                                deduction: 0,
                                get netToPay() {
                                    return Math.max(0, this.pendingSalary - (parseFloat(this.deduction) || 0));
                                }
                            }">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $data['teacher']->id }}">
                                <input type="hidden" name="month" value="{{ $month }}">
                                <input type="hidden" name="year" value="{{ $year }}">
                                
                                @if($data['pending_loan'] > 0)
                                    <div class="bg-amber-50/50 border border-amber-100/50 p-4 rounded-2xl mb-2">
                                        <div class="relative group">
                                            <label class="text-[8px] font-black text-amber-600 uppercase tracking-widest mb-1 block ml-2">Descontar de esta Nómina</label>
                                            <input type="number" name="loan_deduction" x-model.number="deduction" min="0" :max="Math.min(pendingSalary, pendingLoan)" class="w-full text-xs font-black rounded-xl border-amber-200 focus:ring-amber-500 focus:border-amber-500 py-1.5 px-3 bg-white" placeholder="Ej: 50000">
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="relative group">
                                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1 block ml-2">{{ __('Support Attachment') }} ({{ __('Optional') }})</label>
                                    <input type="file" name="attachment" class="w-full text-[10px] text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-gray-100 file:text-gray-500 hover:file:bg-gray-200 transition-all cursor-pointer">
                                </div>

                                <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-club-primary transition-all active:scale-[0.98]">
                                    {{ __('Register Payment of') }} $<span x-text="new Intl.NumberFormat().format(netToPay)"></span>
                                </button>
                            </form>
                        @else
                            <div class="w-full py-4 bg-green-50 text-green-600 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-green-100 flex items-center justify-center">
                                <i class="bi bi-check-circle-fill mr-2"></i> {{ __('Payroll up to date') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($salaryData->isEmpty())
                <div class="bg-white p-20 rounded-[3rem] border-2 border-dashed border-gray-200 text-center">
                    <i class="bi bi-people text-6xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 font-bold italic">No hay profesores registrados con el rol correspondiente.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

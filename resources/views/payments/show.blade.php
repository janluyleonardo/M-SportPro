<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('payments.index') }}"
                class="p-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <h2 class="font-bold text-xl text-black">
                Historial de Pagos
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{}">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Perfil y Estado de Deuda -->
            <div
                class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-6">
                    <div
                        class="h-24 w-24 bg-gray-50 rounded-[2rem] flex items-center justify-center border border-gray-100 overflow-hidden relative shadow-inner group">
                        @php
                            $initials = substr($student->nomDeportista, 0, 1);
                         @endphp

                        <span
                            class="absolute text-3xl font-black text-gray-200 uppercase group-hover:scale-110 transition-transform">{{ $initials }}</span>

                        @if(!empty($student->Photo))
                            <img src="{{ asset($student->Photo) }}"
                                class="absolute inset-0 h-full w-full object-cover rounded-[2rem] z-10"
                                onerror="this.style.display='none'">
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 uppercase mb-1">{{ $student->nomDeportista }}</h1>
                        <div class="flex flex-wrap gap-2 items-center">
                            <span
                                class="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100 uppercase tracking-widest">
                                {{ $student->Categoria }}
                            </span>
                            <span
                                class="text-[10px] font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100 uppercase">
                                ID: {{ $student->numDocumento }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <!-- Resumen de Deuda -->
                    <div
                        class="flex-1 md:flex-none px-6 py-4 rounded-2xl border-2 {{ $student->balance > 0 ? 'bg-red-50 border-red-100 text-red-600' : 'bg-green-50 border-green-100 text-green-600' }} text-center">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-70 mb-1">Saldo Pendiente</p>
                        <p class="text-2xl font-black">${{ number_format($student->balance, 0, ',', '.') }}</p>
                    </div>

                    <!-- Botón de Acción Directo (Solo Admin) -->
                    @role('Admin')
                    <button @click="$dispatch('open-payment-modal')"
                        class="flex-1 md:flex-none px-8 py-5 bg-club-primary hover:opacity-90 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 transform active:scale-95 transition-all text-[10px] uppercase tracking-widest flex items-center justify-center">
                        <i class="bi bi-plus-circle-fill mr-2"></i> Registrar Pago
                    </button>
                    @endrole
                </div>
            </div>
            </div>

            <!-- Listado de Estados por Mes -->
            <div class="mt-12 max-w-2xl mx-auto">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center justify-center">
                    <i class="bi bi-clock-history mr-2"></i> Línea de Tiempo de Mensualidades
                </h3>

                <div class="space-y-3">
                    @forelse($monthStatuses as $status)
                        <div
                            class="bg-white p-3 rounded-xl shadow-sm border {{ $status['is_paid'] ? 'border-gray-100' : 'border-red-100 bg-red-50/5' }} flex items-center justify-between group hover:shadow-md transition-all">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $status['is_paid'] ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    <i class="bi {{ $status['is_paid'] ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-sm">{{ $status['month_name'] }}
                                        {{ $status['year'] }}</h4>
                                    <div class="flex items-center">
                                        @if ($status['is_paid'])
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">
                                                Saldado Totalmente
                                            </span>
                                        @elseif ($status['covered'] > 0)
                                            <span class="text-[9px] font-black text-orange-500 uppercase tracking-wider">
                                                Abono Parcial: ${{ number_format($status['covered'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-black text-red-500 uppercase tracking-wider">
                                                Pendiente
                                            </span>
                                            @if ($status['is_late'])
                                                <span
                                                    class="ml-2 text-[8px] font-black text-red-400 bg-red-50 px-1.5 py-0.5 rounded-md border border-red-100">
                                                    Con Recargo
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Requerido</p>
                                    <p class="text-base font-black {{ $status['is_paid'] ? 'text-gray-900' : ($status['covered'] > 0 ? 'text-orange-600' : 'text-red-600') }}">
                                        ${{ number_format($status['amount'], 0, ',', '.') }}
                                    </p>
                                    @if(!$status['is_paid'] && $status['covered'] > 0)
                                        <p class="text-[9px] font-bold text-red-400 uppercase tracking-widest mt-0.5">
                                            Faltan: ${{ number_format($status['pending'], 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>

                                @if ($status['is_paid'])
                                    @role('Admin')
                                        <div class="border-l border-gray-100 pl-4 flex items-center space-x-2">
                                            @php
                                                $monthPayments = $payments
                                                    ->where('month', $status['month_num'])
                                                    ->where('year', $status['year']);
                                                
                                                // Intentamos obtener el primero para el contador de clases
                                                $paymentObj = $monthPayments->first();
                                            @endphp
                                            @if ($paymentObj)
                                                <div class="flex items-center bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">
                                                    <span class="text-[9px] font-black text-gray-400 mr-1">Asis:</span>
                                                    <span class="text-[10px] font-black text-gray-900 mr-1">{{ $paymentObj->classes_used }}
                                                        / 8</span>
                                                    <form action="{{ route('payments.update', $paymentObj) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="increment_classes" value="1">
                                                        <button type="submit" class="text-club-primary hover:scale-110 transition-transform">
                                                            <i class="bi bi-plus-circle-fill text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endrole
                                @else
                                    @role('Admin')
                                        <div class="border-l border-gray-100 pl-4">
                                            <button
                                                @click="$dispatch('open-payment-modal', { month: {{ $status['month_num'] }}, year: {{ $status['year'] }} })"
                                                class="px-3 py-1.5 bg-red-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-red-700 transition-colors">
                                                Abonar
                                            </button>
                                        </div>
                                    @endrole
                                @endif
                            </div>
                        </div>

                        {{-- Detalle de Abonos para este mes --}}
                        @php
                            $monthAbonos = $payments->where('month', $status['month_num'])->where('year', $status['year']);
                        @endphp
                        @if($monthAbonos->count() > 0)
                            <div class="mx-6 mb-4 -mt-2 bg-gray-50/50 rounded-b-xl border-x border-b border-gray-100 p-2 space-y-1">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest px-2 mb-1">Historial de abonos registrados para este mes:</p>
                                @foreach($monthAbonos as $abono)
                                    <div class="flex items-center justify-between bg-white px-3 py-1.5 rounded-lg border border-gray-100 text-[10px]">
                                        <div class="flex items-center text-gray-600">
                                            <i class="bi bi-calendar-check mr-2 text-club-primary"></i>
                                            <span class="font-bold">{{ \Carbon\Carbon::parse($abono->paid_at)->format('d/m/Y') }}</span>
                                            @if($abono->notes)
                                                <span class="ml-2 text-gray-400 italic">- {{ $abono->notes }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center">
                                            <span class="font-black text-gray-900">${{ number_format($abono->amount, 0, ',', '.') }}</span>
                                            @role('Admin')
                                                <form action="{{ route('payments.destroy', $abono) }}" method="POST" class="ml-2"
                                                    onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar abono', '¿Estás seguro de eliminar este abono de ${{ number_format($abono->amount, 0, ',', '.') }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-300 hover:text-red-500 transition-colors">
                                                        <i class="bi bi-trash text-[10px]"></i>
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <div class="p-20 bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 text-center">
                            <i class="bi bi-calendar-x text-5xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-400 font-bold italic">No se puede determinar el historial (falta fecha de
                                inscripción).</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @role('Admin')
    <!-- Modal de Pago con Lógica de Recargo Dinámica -->
    <div id="modal-pago" x-data="{ 
            open: false, 
            loading: false,
            baseAmount: {{ config('app.default_payment_amount', 50000) }},
            month: {{ date('n') }},
            year: {{ date('Y') }},
            currentMonth: {{ date('n') }},
            currentYear: {{ date('Y') }},
            paidAt: '{{ date('Y-m-d') }}',
            threshold: {{ config('app.payment_late_day_threshold', 10) }},
            percentage: {{ config('app.payment_late_fee_percentage', 10) }},
            hasLateFee: false,
            get totalWithFee() {
                if (this.hasLateFee) {
                    return Math.round(this.baseAmount * (1 + (this.percentage / 100)));
                }
                return this.baseAmount;
            },
            calculate() {
                let selectedMonth = parseInt(this.month);
                let selectedYear = parseInt(this.year);
                let day = parseInt(this.paidAt.split('-')[2]);
                
                this.hasLateFee = false;
                if (selectedYear < this.currentYear) {
                    this.hasLateFee = true;
                } else if (selectedYear === this.currentYear) {
                    if (selectedMonth < this.currentMonth) {
                        this.hasLateFee = true;
                    } else if (selectedMonth === this.currentMonth && day > this.threshold) {
                        this.hasLateFee = true;
                    }
                }
            }
         }"
        x-init="calculate(); $watch('paidAt', () => calculate()); $watch('month', () => calculate()); $watch('year', () => calculate())"
        @open-payment-modal.window="
            open = true; 
            if($event.detail) { 
                month = $event.detail.month || month; 
                year = $event.detail.year || year; 
                calculate(); 
            }
        "
        x-cloak x-show="open" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300"
                @click.away="open = false">
                <div class="p-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-black">Nuevo Pago</h2>
                        <button @click="open = false" class="text-gray-400 hover:text-black">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('payments.store') }}" method="POST" class="space-y-5"
                        @submit="loading = true">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Mes</label>
                                <select name="month" x-model="month"
                                    class="w-full border-gray-200 rounded-xl mt-1 p-3 font-bold text-black" required>
                                    @php $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; @endphp
                                    @foreach($meses as $index => $mes)
                                        <option value="{{ $index + 1 }}">{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Año</label>
                                <select name="year" x-model="year"
                                    class="w-full border-gray-200 rounded-xl mt-1 p-3 font-bold text-black" required>
                                    @foreach(range(date('Y') - 1, date('Y') + 1) as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Monto Recibido ($)</label>
                                <input type="number" name="amount" x-model="baseAmount"
                                    class="w-full border-gray-200 rounded-xl mt-1 p-3 font-black text-lg text-black"
                                    placeholder="Ej: 55000"
                                    required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Fecha de Pago</label>
                                <input type="date" name="paid_at" x-model="paidAt"
                                    class="w-full border-gray-200 rounded-xl mt-1 p-3 font-bold text-black" required>
                            </div>
                        </div>

                        <div :class="hasLateFee ? 'bg-red-50 border-red-100' : 'bg-green-50 border-green-100'"
                            class="p-4 rounded-xl border transition-colors duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest"
                                        :class="hasLateFee ? 'text-red-600' : 'text-green-600'"
                                        x-text="hasLateFee ? 'Mes con recargo (10%)' : 'Mes sin recargo'"></p>
                                    <p class="text-xs font-bold text-gray-500"
                                        x-text="hasLateFee ? 'El abono cubrirá primero el recargo y luego la base.' : 'El abono cubrirá la base del mes.'">
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Monto a Registrar</p>
                                    <p class="text-2xl font-black"
                                        :class="hasLateFee ? 'text-red-600' : 'text-club-primary'">
                                        $<span x-text="new Intl.NumberFormat('es-CO').format(baseAmount)"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" @click="open = false" :disabled="loading"
                                class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl disabled:opacity-50">
                                Cerrar
                            </button>
                            <button type="submit" :disabled="loading"
                                class="flex-[2] py-4 bg-club-primary text-white font-black rounded-2xl shadow-lg shadow-blue-200 hover:scale-[1.02] transition-all disabled:opacity-50 flex items-center justify-center">
                                <span x-show="!loading">Guardar Pago</span>
                                <span x-show="loading" class="flex items-center">
                                    <i class="bi bi-arrow-repeat animate-spin mr-2"></i> Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endrole
</x-app-layout>
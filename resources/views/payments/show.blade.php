<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('payments.index') }}"
                class="p-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors">
                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
            </a>
            <h2 class="font-bold text-xl text-black">
                {{ __('Payment History') }} - {{ $student->nomDeportista ?? 'Estudiante' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{}">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Perfil y Estado de Deuda -->
            <div
                class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-6 flex-1 min-w-0">
                    <div
                        class="h-20 w-20 flex-shrink-0 bg-gray-50 rounded-[1.8rem] flex items-center justify-center border border-gray-100 overflow-hidden relative shadow-inner group">
                        @php
                            $initials = substr($student->nomDeportista, 0, 1);
                        @endphp

                        <span
                            class="absolute text-2xl font-black text-gray-200 uppercase group-hover:scale-110 transition-transform">{{ $initials }}</span>

                        @if(!empty($student->Photo))
                            <img src="{{ asset($student->Photo) }}"
                                class="absolute inset-0 h-full w-full object-cover rounded-[1.8rem] z-10"
                                onerror="this.style.display='none'">
                        @endif
                    </div>
                    <div class="min-w-0">
                        @php
                            $nameParts = explode(' ', $student->nomDeportista);
                            $count = count($nameParts);
                            if ($count >= 4) {
                                $firstName = implode(' ', array_slice($nameParts, 0, 2));
                                $lastName = implode(' ', array_slice($nameParts, 2));
                            } elseif ($count == 3) {
                                $firstName = $nameParts[0];
                                $lastName = implode(' ', array_slice($nameParts, 1));
                            } else {
                                $firstName = $nameParts[0];
                                $lastName = $nameParts[1] ?? '';
                            }
                        @endphp
                        <h1 class="flex flex-col leading-none">
                            <span class="text-xl md:text-2xl font-black text-gray-900 uppercase">{{ $firstName }}</span>
                            <span class="text-2xl md:text-3xl font-black text-club-primary uppercase">{{ $lastName }}</span>
                        </h1>
                        <div class="flex flex-wrap gap-2 items-center mt-2">
                            <span
                                class="text-[9px] font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100 uppercase tracking-widest">
                                {{ __($student->Categoria) }}
                            </span>
                            <span
                                class="text-[9px] font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100 uppercase">
                                ID: {{ $student->numDocumento }}
                            </span>
                            @if($student->becado)
                                <span class="text-[9px] font-black text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200 uppercase tracking-widest">
                                    <i class="bi bi-award-fill mr-1"></i> Becado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center lg:justify-end gap-3 w-full lg:w-auto">
                    <!-- Resumen de Deuda -->
                    <div
                        class="px-5 py-3 rounded-2xl border-2 {{ $student->balance > 0 ? 'bg-red-50 border-red-100 text-red-600' : 'bg-green-50 border-green-100 text-green-600' }} text-center min-w-[140px]">
                        <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-70 mb-0.5">{{ __('Pending Balance') }}</p>
                        <p class="text-xl font-black">${{ number_format($student->balance, 0, ',', '.') }}</p>
                    </div>

                    <!-- Botón de Acción Directo (Solo Admin) -->
                    @role('Admin|SubAdmin')
                    @if($student->balance > 0)
                        <button @click="$dispatch('open-payment-modal')"
                            class="px-6 py-4 bg-club-primary hover:opacity-90 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 transform active:scale-95 transition-all text-[9px] uppercase tracking-widest flex items-center justify-center whitespace-nowrap">
                            <i class="bi bi-plus-circle-fill mr-2"></i> {{ __('Register Payment') }}
                        </button>
                    @else
                        <button disabled
                            class="px-6 py-4 bg-gray-100 text-gray-400 font-black rounded-2xl border border-gray-200 cursor-not-allowed text-[9px] uppercase tracking-widest flex items-center justify-center whitespace-nowrap">
                            <i class="bi bi-check-circle-fill mr-2 text-green-500"></i> {{ __('Fully Paid') }}
                        </button>
                    @endif
                    @endrole
                </div>
            </div>

            <!-- Listado de Estados por Mes -->
            <div class="mt-12 max-w-2xl mx-auto">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center justify-center">
                    <i class="bi bi-clock-history mr-2"></i> {{ __('Monthly Payment Timeline') }}
                </h3>

                <div class="space-y-3">
                    @forelse($monthStatuses as $status)
                        <div
                            class="bg-white p-3 rounded-xl shadow-sm border {{ $status['is_paid'] ? 'border-gray-100' : ($status['is_late'] ? 'border-red-100 bg-red-50/5' : 'border-blue-100 bg-blue-50/5') }} flex items-center justify-between group hover:shadow-md transition-all">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $status['is_paid'] ? 'bg-green-50 text-green-600' : ($status['is_late'] ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600') }}">
                                    <i class="bi {{ $status['is_paid'] ? 'bi-check-circle-fill' : ($status['is_late'] ? 'bi-exclamation-circle-fill' : 'bi-calendar-event-fill') }}"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-sm">{{ $status['month_name'] }}
                                        {{ $status['year'] }}</h4>
                                    <div class="flex items-center">
                                        @if ($status['is_paid'])
                                            <div class="flex flex-col gap-0.5">
                                                    <span class="text-[9px] font-bold text-green-600 uppercase tracking-wider">
                                                        {{ $student->becado ? 'Becado - Sin cobro' : __('Fully Paid') }}
                                                </span>
                                                @if($status['paid_at'])
                                                    <span class="text-[8px] font-medium text-gray-400 italic">
                                                        Pagado el {{ \Carbon\Carbon::parse($status['paid_at'])->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                                @if(($status['carry_used'] ?? 0) > 0)
                                                    <span class="text-[8px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md border border-emerald-100 w-fit">
                                                        <i class="bi bi-arrow-left-circle-fill mr-0.5"></i>
                                                        Crédito aplicado: ${{ number_format($status['carry_used'], 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if(($status['surplus_generated'] ?? 0) > 0)
                                                    <span class="text-[8px] font-black text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-md border border-blue-100 w-fit">
                                                        <i class="bi bi-arrow-right-circle-fill mr-0.5"></i>
                                                        Genera crédito: ${{ number_format($status['surplus_generated'], 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if(($status['waive_late_fee'] ?? false))
                                                    <span class="text-[8px] font-black text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded-md border border-orange-200 w-fit">
                                                        <i class="bi bi-shield-check mr-0.5"></i> Recargo exonerado por autorización
                                                    </span>
                                                @endif
                                            </div>
                                        @elseif ($status['covered'] > 0)
                                            <span class="text-[9px] font-black text-orange-500 uppercase tracking-wider">
                                                {{ __('Partial Payment') }}: ${{ number_format($status['covered'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-black uppercase tracking-wider {{ $status['is_late'] ? 'text-red-500' : 'text-blue-500' }}">
                                                {{ $status['is_late'] ? 'Vencido' : 'Pendiente (A tiempo)' }}
                                            </span>
                                            @if ($status['is_late'])
                                                <span
                                                    class="ml-2 text-[8px] font-black text-red-400 bg-red-50 px-1.5 py-0.5 rounded-md border border-red-100">
                                                    {{ __('With Surcharge') }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Requerido</p>
                                    <p class="text-base font-black {{ $status['is_paid'] ? 'text-gray-900' : ($status['is_late'] ? 'text-red-600' : 'text-blue-600') }}">
                                        ${{ number_format($status['amount'], 0, ',', '.') }}
                                    </p>
                                    @if(!$status['is_paid'] && $status['covered'] > 0)
                                        <p class="text-[9px] font-bold text-red-400 uppercase tracking-widest mt-0.5">
                                            Faltan: ${{ number_format($status['pending'], 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>

                                @if ($status['is_paid'])
                                    @role('Admin|SubAdmin')
                                        <div class="border-l border-gray-100 pl-4 flex items-center space-x-2">
                                            @php
                                                // Buscamos directamente en la base de datos para asegurar el dato más fresco
                                                $slot = \App\Models\AttendanceSlot::where('student_id', $student->id)
                                                    ->where('month', $status['month_num'])
                                                    ->where('year', $status['year'])
                                                    ->first();
                                            @endphp
                                            @if ($slot)
                                                <div class="flex items-center bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">
                                                    <span class="text-[9px] font-black text-gray-400 mr-1">Asis:</span>
                                                    <span class="text-[10px] font-black text-gray-900 mr-1">{{ $slot->classes_used }}
                                                        / {{ $slot->classes_allowed }}</span>
                                                    <form action="{{ route('payments.update', $slot->id) }}" method="POST">
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
                                    @role('Admin|SubAdmin')
                                        <div class="border-l border-gray-100 pl-4">
                                            <button
                                                @click="$dispatch('open-payment-modal', { month: {{ $status['month_num'] }}, year: {{ $status['year'] }} })"
                                                class="px-3 py-1.5 text-white text-[9px] font-black uppercase tracking-widest rounded-lg transition-colors {{ $status['is_late'] ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                                                {{ __('Pay') }}
                                            </button>
                                        </div>
                                    @endrole
                                    @unlessrole('Admin|Profesor')
                                        <div class="border-l border-gray-100 pl-4">
                                            <button
                                                @click="$dispatch('open-voucher-modal', { month: {{ $status['month_num'] }}, year: {{ $status['year'] }}, amount: {{ $status['amount'] }}, monthName: '{{ $status['month_name'] }}' })"
                                                class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[9px] font-black uppercase tracking-widest rounded-lg transition-colors">
                                                <i class="bi bi-cloud-upload-fill mr-1"></i> {{ __('Upload Receipt') }}
                                            </button>
                                        </div>
                                    @endunlessrole
                                @endif
                            </div>
                        </div>

                        {{-- Detalle de Abonos para este mes --}}
                        @php
                            $monthAbonos = $payments->where('month', $status['month_num'])->where('year', $status['year']);
                        @endphp
                        @if($monthAbonos->count() > 0)
                            <div class="mx-6 mb-4 -mt-2 bg-gray-50/50 rounded-b-xl border-x border-b border-gray-100 p-2 space-y-1">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest px-2 mb-1">{{ __('Payment history recorded for this month') }}:</p>
                                @foreach($monthAbonos as $abono)
                                    <div class="flex items-center justify-between bg-white px-3 py-1.5 rounded-lg border border-gray-100 text-[10px]">
                                        <div class="flex flex-col">
                                            <div class="flex items-center text-gray-600">
                                                <i class="bi bi-calendar-check mr-2 text-club-primary"></i>
                                                <span class="font-bold">{{ \Carbon\Carbon::parse($abono->paid_at)->format('d/m/Y h:i A') }}</span>
                                                @if($abono->user)
                                                    <span class="ml-2 text-gray-500 font-medium italic"> - Por: {{ $abono->user->name }}</span>
                                                @endif
                                            </div>
                                            @if($abono->notes)
                                                <div class="ml-6 text-[9px] text-gray-400 italic mt-0.5">{{ $abono->notes }}</div>
                                            @endif
                                            @if($abono->voucher)
                                                <div class="ml-6 mt-2 flex items-center gap-2">
                                                    <a href="{{ asset($abono->voucher) }}" target="_blank" class="flex items-center px-2 py-1 bg-indigo-50 text-indigo-600 rounded border border-indigo-100 hover:bg-indigo-100 transition-colors">
                                                        <i class="bi bi-file-earmark-image mr-1"></i> {{ __('View Receipt') }}
                                                    </a>
                                                    @if($abono->voucher_status == 'pending')
                                                        <span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded border border-yellow-100 font-bold uppercase tracking-widest text-[8px]">{{ __('Pending Balance') }}</span>
                                                    @elseif($abono->voucher_status == 'approved')
                                                        <span class="px-2 py-1 bg-green-50 text-green-600 rounded border border-green-100 font-bold uppercase tracking-widest text-[8px]">{{ __('Fully Paid') }}
                                                    @elseif($abono->voucher_status == 'rejected')
                                                        <span class="px-2 py-1 bg-red-50 text-red-600 rounded border border-red-100 font-bold uppercase tracking-widest text-[8px]">{{ __('Rejected') }}: {{ $abono->rejection_reason }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="font-black text-gray-900">${{ number_format($abono->amount, 0, ',', '.') }}</span>

                                            @role('Admin|SubAdmin')
                                                @if($abono->voucher && $abono->voucher_status == 'pending')
                                                    <div class="flex gap-1" x-data="{ rejecting: false }">
                                                        <form action="{{ route('payments.verify', $abono) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="p-1 bg-green-500 text-white rounded hover:bg-green-600 transition-colors" title="Aprobar Pago">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                        </form>
                                                        <button @click="rejecting = true" class="p-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors" title="Rechazar Comprobante">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>

                                                        <!-- Modal Mini para rechazo -->
                                                        <template x-if="rejecting">
                                                            <div class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                                                                <div class="bg-white p-6 rounded-2xl w-full max-w-xs shadow-2xl" @click.away="rejecting = false">
                                                                    <h5 class="font-black text-sm mb-4">{{ __('Rejection Reason') }}</h5>
                                                                    <form action="{{ route('payments.reject', $abono) }}" method="POST">
                                                                        @csrf
                                                                        <textarea name="rejection_reason" class="w-full border-gray-200 rounded-xl text-xs mb-4" placeholder="Ej: No se ve bien la fecha, monto incorrecto..." required></textarea>
                                                                        <div class="flex gap-2">
                                                                            <button type="button" @click="rejecting = false" class="flex-1 py-2 bg-gray-100 rounded-lg text-xs font-bold">{{ __('Cancel') }}</button>
                                                                            <button type="submit" class="flex-1 py-2 bg-red-600 text-white rounded-lg text-xs font-black">{{ __('Confirm') }}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                @endif
                                            @endrole

                                            {{-- Eliminar abono: SOLO Admin --}}
                                            @role('Admin')
                                                <form action="{{ route('payments.destroy', $abono) }}" method="POST"
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
                            <p class="text-gray-400 font-bold italic">{{ __('No payment history available (missing enrollment date).') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @role('Admin|SubAdmin')
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
            waiveLateFee: false,
            get totalWithFee() {
                if (this.hasLateFee && !this.waiveLateFee) {
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
                // Si se cambia a un mes sin recargo, desactivar la exoneración
                if (!this.hasLateFee) { this.waiveLateFee = false; }
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
                                <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Amount Received') }} ($)</label>
                                <input type="number" name="amount" x-model="baseAmount"
                                    class="w-full border-gray-200 rounded-xl mt-1 p-3 font-black text-lg text-black"
                                    placeholder="Ej: 55000"
                                    required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Payment Date') }}</label>
                                <input type="date" name="paid_at" x-model="paidAt"
                                    class="w-full border-gray-200 rounded-xl mt-1 p-3 font-bold text-black" required>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Observations / Notes') }}
                                <span x-show="waiveLateFee" class="text-red-500 ml-1">*Obligatorio</span>
                            </label>
                            <textarea name="notes" rows="2"
                                :required="waiveLateFee"
                                class="w-full border-gray-200 rounded-xl mt-1 p-3 text-sm text-gray-600 font-medium"
                                :class="waiveLateFee ? 'border-orange-300 ring-1 ring-orange-200' : ''"
                                placeholder="{{ __('E.g.: Cash payment, Nequi, debt payment, etc. (Optional)') }}"></textarea>
                        </div>

                        {{-- Checkbox de exoneración: solo visible si hay recargo --}}
                        <div x-show="hasLateFee" x-cloak
                            class="flex items-start gap-3 p-3 rounded-xl border"
                            :class="waiveLateFee ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-100'">
                            <input type="checkbox" id="waive_late_fee_check"
                                x-model="waiveLateFee"
                                class="mt-0.5 w-4 h-4 text-orange-500 rounded border-gray-300 cursor-pointer">
                            <input type="hidden" name="waive_late_fee" :value="waiveLateFee ? '1' : '0'">
                            <label for="waive_late_fee_check" class="cursor-pointer flex-1">
                                <span class="text-[10px] font-black text-orange-600 uppercase tracking-widest block">
                                    <i class="bi bi-shield-check mr-1"></i> Exonerar pago tardío
                                </span>
                                <span class="text-[9px] text-gray-500 font-medium">
                                    Autorizado por el dueño. Se acepta solo el valor base sin recargo.
                                    La observación es obligatoria.
                                </span>
                            </label>
                        </div>

                        <div :class="hasLateFee && !waiveLateFee ? 'bg-red-50 border-red-100' : (waiveLateFee ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-100')"
                            class="p-4 rounded-xl border transition-colors duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest"
                                        :class="hasLateFee && !waiveLateFee ? 'text-red-600' : (waiveLateFee ? 'text-orange-600' : 'text-green-600')"
                                        x-text="waiveLateFee ? '⚠️ Recargo exonerado (autorizado)' : (hasLateFee ? 'Mes con recargo (10%)' : 'Mes sin recargo')"></p>
                                    <p class="text-xs font-bold text-gray-500"
                                        x-text="waiveLateFee ? 'Se registra solo el valor base. La observación quedará como constancia.' : (hasLateFee ? 'El abono cubrirá primero el recargo y luego la base.' : 'El abono cubrirá la base del mes.')">
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">{{ __('Amount to Register') }}</p>
                                    <p class="text-2xl font-black"
                                        :class="hasLateFee && !waiveLateFee ? 'text-red-600' : (waiveLateFee ? 'text-orange-500' : 'text-club-primary')">
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
                                <span x-show="!loading">{{ __('Save Payment') }}</span>
                                <span x-show="loading" class="flex items-center">
                                    <i class="bi bi-arrow-repeat animate-spin mr-2"></i> {{ __('Processing payment...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endrole

    <!-- Modal para Subir Comprobante (Padres/Deportistas) -->
    <div id="modal-voucher" x-data="{
            open: false,
            loading: false,
            month: 1,
            year: 2024,
            amount: 50000,
            monthName: ''
        }"
        @open-voucher-modal.window="
            open = true;
            month = $event.detail.month;
            year = $event.detail.year;
            amount = $event.detail.amount;
            monthName = $event.detail.monthName;
        "
        x-cloak x-show="open" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300"
                @click.away="open = false">
                <div class="p-10 text-center">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-cloud-arrow-up-fill text-4xl"></i>
                    </div>

                    <h2 class="text-2xl font-black text-black mb-2">{{ __('Upload receipt') }}</h2>
                    <p class="text-sm text-gray-500 mb-8 font-medium">{{ __('Upload the photo or PDF of your payment for') }} <span class="text-indigo-600 font-bold" x-text="monthName + ' ' + year"></span></p>

                    <form action="{{ route('payments.upload_voucher') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-left"
                        @submit="loading = true">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="month" x-model="month">
                        <input type="hidden" name="year" x-model="year">
                        <input type="hidden" name="amount" x-model="amount">

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">{{ __('Select your file') }}</label>
                            <input type="file" name="voucher" accept="image/*,application/pdf"
                                class="w-full border-2 border-dashed border-gray-200 rounded-2xl p-4 text-sm font-bold text-gray-500 bg-gray-50 hover:bg-white hover:border-indigo-300 transition-all cursor-pointer"
                                required>
                            <p class="text-[9px] text-gray-400 mt-2 px-2 italic text-center">{{ __('Accepted formats: JPG, PNG, PDF. Max 5MB.') }}</p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">{{ __('Additional Note (Optional)') }}</label>
                            <textarea name="notes" rows="2"
                                class="w-full border-gray-200 rounded-2xl p-4 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ej: Pago realizado por Nequi #123456"></textarea>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" @click="open = false" :disabled="loading"
                                class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl disabled:opacity-50">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="loading"
                                class="flex-[2] py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-100 hover:scale-[1.02] transition-all disabled:opacity-50 flex items-center justify-center">
                                <span x-show="!loading">{{ __('Upload receipt') }}</span>
                                <span x-show="loading" class="flex items-center">
                                    <i class="bi bi-arrow-repeat animate-spin mr-2"></i> {{ __('Processing payment...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

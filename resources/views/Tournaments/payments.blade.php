<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('tournaments.index') }}" class="p-2 bg-gray-100 rounded-lg text-gray-600 hover:bg-gray-200 transition-colors">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                        {{ __('Control de Pagos') }}
                    </h2>
                    <p class="text-sm text-gray-500 font-medium">{{ $tournament->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="px-4 py-2 bg-green-50 rounded-xl border border-green-100 flex items-center">
                    <i class="bi bi-cash-stack text-green-600 mr-2"></i>
                    <span class="text-xs font-black text-green-700 uppercase tracking-widest">Resumen Financiero</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- TARJETAS DE REFERENCIA DE COSTOS (Visual Aid) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div class="bg-indigo-50 p-3 rounded-2xl text-indigo-600">
                        <i class="bi bi-trophy-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Inscripción Torneo</p>
                        <p class="text-xl font-black text-gray-900">${{ number_format($tournament->costo_total_inscripcion, 0) }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div class="bg-blue-50 p-3 rounded-2xl text-blue-600">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alumnos en Planilla</p>
                        <p class="text-xl font-black text-gray-900">{{ $tournament->students->count() }} Jugadores</p>
                    </div>
                </div>

                @php 
                    $studentCount = $tournament->students->count();
                    $individualInscription = $studentCount > 0 ? (float)$tournament->costo_total_inscripcion / $studentCount : 0;
                @endphp
                <div class="bg-club-primary p-6 rounded-3xl shadow-lg border border-club-primary/10 flex items-center space-x-4">
                    <div class="bg-white/20 p-3 rounded-2xl text-white">
                        <i class="bi bi-person-badge-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-white/70 uppercase tracking-widest">Inscripción por Jugador</p>
                        <p class="text-xl font-black text-white">${{ number_format($individualInscription, 0) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- RESUMEN GENERAL DEL TORNEO (Unified Ledger) -->
            @if(!$programmings->isEmpty())
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-club-primary border-b border-club-primary/10 flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-xl text-white">
                                <i class="bi bi-wallet2 text-xl"></i>
                            </div>
                            <h3 class="text-white font-black uppercase tracking-widest text-sm">Estado de Cuenta General del Torneo</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Deportista</th>
                                    <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Cargos Totales
                                        <span class="block text-[8px] font-bold text-gray-300 normal-case">(Inscrip. Inicial + Partidos)</span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pagado</th>
                                    <th class="px-6 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Saldo Pendiente</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @php 
                                    $studentCount = $tournament->students->count();
                                    $individualInscription = $studentCount > 0 ? (float)$tournament->costo_total_inscripcion / $studentCount : 0;
                                    
                                    $allStudentsData = [];
                                    // Inicializar con la inscripción individual (el arbitraje se acumulará por partido)
                                    foreach($tournament->students as $student) {
                                        $allStudentsData[$student->id] = [
                                            'name' => $student->nomDeportista, 
                                            'total_cost' => $individualInscription, 
                                            'total_paid' => 0
                                        ];
                                    }

                                    foreach($programmings as $prog) {
                                        foreach($prog->summoned_data as $data) {
                                            $sid = $data['student_id'];
                                            if(isset($allStudentsData[$sid])) {
                                                // Sumamos el costo de arbitraje del partido al total del jugador
                                                $allStudentsData[$sid]['total_cost'] += (float)$prog->costo_arbitraje;
                                                $allStudentsData[$sid]['total_paid'] += ($data['pagado_inscripcion'] + $data['pagado_arbitraje']);
                                            }
                                        }
                                    }
                                    $grandTotalDebt = 0;
                                @endphp
                                @foreach($allStudentsData as $sid => $data)
                                    @php $debt = $data['total_cost'] - $data['total_paid']; $grandTotalDebt += $debt; @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $data['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500">${{ number_format($data['total_cost'], 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-black text-green-600">${{ number_format($data['total_paid'], 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="px-3 py-1 rounded-lg {{ $debt > 0 ? 'bg-red-50 text-red-700 font-black' : 'bg-green-50 text-green-700 font-black' }} text-sm">
                                                ${{ number_format($debt, 0) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Deuda Total de la Planilla:</td>
                                    <td class="px-6 py-4 text-right text-lg font-black text-red-600">${{ number_format($grandTotalDebt, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            @if($programmings->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-receipt-cutoff text-4xl text-gray-200"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-1">No hay registros de pagos</h4>
                    <p class="text-gray-500">Aún no se han programado partidos para este torneo o no se han registrado abonos.</p>
                </div>
            @else
                @foreach($programmings as $index => $prog)
                    @php 
                        // Un partido es "editable" si es el primero (más reciente)
                        $isEditable = ($index === 0); 
                    @endphp
                    <div x-data="{ open: {{ $isEditable ? 'true' : 'false' }} }" 
                         class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 {{ $prog->trashed() ? 'opacity-75 grayscale-[0.5]' : '' }}">
                        
                        <!-- Header del Acordión (Clickable) -->
                        <div @click="open = !open" 
                             class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-100/50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="bg-club-primary text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ \Carbon\Carbon::parse($prog->fecha)->format('d') }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-gray-900 flex items-center">
                                        {{ $prog->eLocal }} vs {{ $prog->eVisitante }}
                                        @if($prog->trashed())
                                            <span class="ml-3 px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-black uppercase rounded-lg">Registro Eliminado</span>
                                        @endif
                                        @if(!$isEditable)
                                            <span class="ml-2 px-2 py-0.5 bg-gray-200 text-gray-600 text-[9px] font-black uppercase rounded-lg"><i class="bi bi-lock-fill mr-1"></i> Histórico</span>
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                                        {{ \Carbon\Carbon::parse($prog->fecha)->translatedFormat('l, d \d\e F') }} - {{ $prog->hora }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6">
                                <div class="hidden md:flex space-x-4 text-right">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-400 font-bold uppercase">Costo Total Partido</span>
                                        <span class="text-xs font-black text-indigo-600">${{ number_format($prog->costo_inscripcion + $prog->costo_arbitraje, 0) }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-400 font-bold uppercase">Recaudado</span>
                                        <span class="text-xs font-black text-green-600">${{ number_format($prog->summoned_data->sum(fn($p) => $p['pagado_inscripcion'] + $p['pagado_arbitraje']), 0) }}</span>
                                    </div>
                                </div>
                                <i class="bi text-gray-300 text-xl transition-transform duration-300" :class="open ? 'bi-chevron-up rotate-0' : 'bi-chevron-down'"></i>
                            </div>
                        </div>

                        <!-- Contenido del Acordión -->
                        <div x-show="open" x-collapse x-cloak>
                            <div x-data="{ 
                                submitting: false,
                                localPayments: @js($prog->summoned_data),
                                async save() {
                                    this.submitting = true;
                                    try {
                                        const response = await fetch('{{ route('programming.payments.update', $prog->id) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({ payments: this.localPayments })
                                        });
                                        if (response.ok) {
                                            window.location.reload();
                                        }
                                    } catch (e) {
                                        console.error(e);
                                        alert('Error al guardar pagos');
                                    } finally {
                                        this.submitting = false;
                                    }
                                }
                            }">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Deportista</th>
                                                <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Saldo Anterior</th>
                                                <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Inscripción</th>
                                                <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Arbitraje</th>
                                                <th class="px-6 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Deuda Actual</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-50">
                                            <template x-for="(p, index) in localPayments" :key="p.student_id">
                                                <tr class="hover:bg-gray-50/50 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-bold text-gray-700" x-text="p.name"></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <span class="text-xs font-bold" :class="p.previous_debt > 0 ? 'text-red-500' : 'text-gray-400'" x-text="'$' + new Intl.NumberFormat().format(p.previous_debt)"></span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        @if($isEditable)
                                                            <div class="relative group max-w-[120px] mx-auto">
                                                                <input type="number" x-model.number="p.pagado_inscripcion" 
                                                                    class="w-full text-center text-xs font-black rounded-xl border-gray-200 focus:ring-green-500 focus:border-green-500 py-1.5 pl-2 pr-2 transition-all"
                                                                    :class="p.pagado_inscripcion >= {{ $prog->costo_inscripcion }} ? 'bg-green-50 text-green-700 border-green-200' : (p.pagado_inscripcion > 0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-gray-50 text-gray-400')">
                                                                <button @click="p.pagado_inscripcion = {{ $prog->costo_inscripcion }}" x-show="p.pagado_inscripcion < {{ $prog->costo_inscripcion }}" title="Marcar la totalidad de la inscripción para este encuentro (${{ number_format($prog->costo_inscripcion, 0) }})" class="absolute -right-2 -top-2 bg-white shadow-sm border border-gray-100 rounded-full p-1 text-green-600 hover:scale-110 transition-transform">
                                                                    <i class="bi bi-check-all text-[10px]"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="text-xs font-black" :class="p.pagado_inscripcion > 0 ? 'text-green-600' : 'text-gray-300'" x-text="'$' + new Intl.NumberFormat().format(p.pagado_inscripcion)"></span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        @if($isEditable)
                                                            <div class="relative group max-w-[120px] mx-auto">
                                                                <input type="number" x-model.number="p.pagado_arbitraje" 
                                                                    class="w-full text-center text-xs font-black rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-2 pr-2 transition-all"
                                                                    :class="p.pagado_arbitraje >= {{ $prog->costo_arbitraje }} ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : (p.pagado_arbitraje > 0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-gray-50 text-gray-400')">
                                                                <button @click="p.pagado_arbitraje = {{ $prog->costo_arbitraje }}" x-show="p.pagado_arbitraje < {{ $prog->costo_arbitraje }}" title="Marcar la totalidad del arbitraje para este encuentro (${{ number_format($prog->costo_arbitraje, 0) }})" class="absolute -right-2 -top-2 bg-white shadow-sm border border-gray-100 rounded-full p-1 text-indigo-600 hover:scale-110 transition-transform">
                                                                    <i class="bi bi-check-all text-[10px]"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="text-xs font-black" :class="p.pagado_arbitraje > 0 ? 'text-indigo-600' : 'text-gray-300'" x-text="'$' + new Intl.NumberFormat().format(p.pagado_arbitraje)"></span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                                        <span class="text-sm font-black" 
                                                            :class="(parseFloat(p.previous_debt) + {{ (float)$prog->costo_arbitraje }} - (parseFloat(p.pagado_inscripcion) || 0) - (parseFloat(p.pagado_arbitraje) || 0)) > 0 ? 'text-red-600' : 'text-green-600'" 
                                                            x-text="'$' + new Intl.NumberFormat().format(parseFloat(p.previous_debt) + {{ (float)$prog->costo_arbitraje }} - (parseFloat(p.pagado_inscripcion) || 0) - (parseFloat(p.pagado_arbitraje) || 0))"></span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                        <tfoot class="bg-gray-50/50">
                                            <tr>
                                                <td colspan="4" class="px-6 py-4">
                                                    @if($isEditable)
                                                        <div class="flex items-center space-x-4">
                                                            <button @click="save" :disabled="submitting" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-xl font-bold text-xs hover:bg-green-700 transition-all shadow-sm disabled:opacity-50">
                                                                <i class="bi bi-save mr-2" x-show="!submitting"></i>
                                                                <svg x-show="submitting" class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                <span x-text="submitting ? 'Guardando...' : 'Guardar Pagos de este Encuentro'"></span>
                                                            </button>
                                                            <div class="text-[9px] text-gray-400 font-bold uppercase leading-tight">
                                                                <i class="bi bi-info-circle mr-1"></i> Solo puedes editar el encuentro más reciente.<br>Los saldos anteriores se arrastran automáticamente.
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                                                            <i class="bi bi-info-circle-fill mr-2"></i> Este registro es histórico y ya tiene abonos procesados.
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Abonado en este partido:</div>
                                                    <div class="text-lg font-black text-club-primary" x-text="'$' + new Intl.NumberFormat().format(localPayments.reduce((acc, p) => acc + (parseFloat(p.pagado_inscripcion) || 0) + (parseFloat(p.pagado_arbitraje) || 0), 0))"></div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                    <i class="bi bi-calendar-event text-xl"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Calendario de Programación') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="calendarApp(@js($tournaments))">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Top Action Bar -->
            <div
                class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <!-- Month Navigation -->
                <div class="flex items-center justify-center space-x-2 sm:space-x-4 w-full md:w-auto">
                    <button @click="prevMonth"
                        class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                        <i class="bi bi-chevron-left text-lg"></i>
                    </button>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 w-32 sm:w-48 text-center capitalize"
                        x-text="monthNames[month] + ' ' + year"></h3>
                    <button @click="nextMonth"
                        class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                        <i class="bi bi-chevron-right text-lg"></i>
                    </button>
                    <button @click="goToToday"
                        class="px-3 py-1.5 text-xs sm:text-sm font-semibold text-club-primary hover:bg-blue-50 rounded-lg transition-colors border border-blue-100">
                        Hoy
                    </button>
                </div>

                @hasanyrole('Admin|Profesor')
                <button @click="openCreateModal = true"
                    class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-club-primary border border-transparent rounded-xl font-bold text-sm text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-club-primary focus:ring-offset-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-plus-circle mr-2 text-lg"></i> {{__('Nueva Programación')}}
                </button>
                @endhasanyrole
            </div>

            <!-- Calendar Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <!-- Days of week -->
                <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50/80">
                    <template x-for="day in ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']">
                        <div class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider"
                            x-text="day"></div>
                    </template>
                </div>

                <!-- Days Grid -->
                <div class="grid grid-cols-7 bg-gray-200 gap-[1px]">
                    <!-- Blank days for start of month -->
                    <template x-for="blank in blankDays">
                        <div class="bg-gray-50 min-h-[100px] p-2 opacity-50"></div>
                    </template>

                    <!-- Actual days -->
                    <template x-for="day in days">
                        <div @click="selectDay(day)"
                            class="bg-white min-h-[100px] p-2 cursor-pointer transition-colors relative group"
                            :class="{'ring-2 ring-inset ring-club-primary bg-blue-50/30': isSelected(day), 'hover:bg-gray-50': !isSelected(day)}">

                            <div class="flex justify-between items-start">
                                <span
                                    class="w-7 h-7 flex items-center justify-center rounded-full text-sm font-semibold"
                                    :class="{'bg-club-primary text-white': isToday(day), 'text-gray-700': !isToday(day)}">
                                    <span x-text="day"></span>
                                </span>

                                <!-- Event Indicator Badge -->
                                <template x-if="hasEvents(day)">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-club-secondary text-gray-900 border border-club-secondary/30">
                                        <span x-text="getEventsCount(day)"></span> <i class="bi bi-controller ml-1"></i>
                                    </span>
                                </template>
                            </div>

                            <!-- Event Preview -->
                            <div class="mt-2 space-y-1">
                                <template x-for="(event, index) in getEventsForDay(day).slice(0, 2)">
                                    <div
                                        class="text-[10px] truncate px-1.5 py-1 bg-blue-50 text-blue-700 rounded font-semibold border border-blue-100">
                                        <span x-text="event.hora"></span> - <span x-text="event.torneo"></span>
                                    </div>
                                </template>
                                <template x-if="getEventsCount(day) > 2">
                                    <div class="text-[10px] text-gray-500 font-semibold pl-1">
                                        + <span x-text="getEventsCount(day) - 2"></span> más...
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Blank days for end of month -->
                    <template x-for="blank in endBlankDays">
                        <div class="bg-gray-50 min-h-[100px] p-2 opacity-50"></div>
                    </template>
                </div>
            </div>

            <!-- Selected Day Details (Shows only when a day is selected) -->
            <div x-show="selectedDate" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-gray-100" style="display: none;">

                <div
                    class="bg-club-primary px-6 py-4 flex justify-between items-center border-b-4 border-club-secondary">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-calendar-check mr-2"></i>
                        Programación para el <span x-text="formatDateHuman(selectedDate)" class="ml-1"></span>
                    </h3>
                    <div class="flex items-center space-x-4">
                        <template x-if="selectedEvents.length > 0">
                            <div class="flex items-center space-x-2">
                                <a :href="whatsappDailyUrl" target="_blank"
                                    title="Enviar programación del día por WhatsApp"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold flex items-center transition-all shadow-md border border-green-400">
                                    <i class="bi bi-whatsapp mr-2"></i>
                                    WhatsApp Día
                                </a>
                                <a href="#"
                                     @click.prevent="if (!printing) { printing = true; window.open('{{ route('programming.imprimir', 'DATE') }}'.replace('DATE', selectedDate), '_blank'); setTimeout(() => printing = false, 3000); }"
                                     :class="printing ? 'opacity-75 cursor-not-allowed pointer-events-none' : ''"
                                    class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-sm font-bold flex items-center transition-all border border-white/20">
                                    <template x-if="!printing">
                                        <i class="bi bi-printer-fill mr-2"></i>
                                    </template>
                                    <template x-if="printing">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </template>
                                    <span x-text="printing ? 'Generando...' : '{{ __('Print PDF') }}'"></span>
                                </a>
                            </div>
                        </template>
                        <button @click="selectedDate = null; selectedEvents = []"
                            class="text-white/70 hover:text-white transition-colors">
                            <i class="bi bi-x-circle-fill text-2xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-0">
                    <template x-if="selectedEvents.length === 0">
                        <div class="p-12 text-center flex flex-col items-center justify-center">
                            <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="bi bi-emoji-smile text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Día Libre</h4>
                            <p class="text-gray-500">No hay partidos programados para esta fecha.</p>
                        </div>
                    </template>

                    <template x-if="selectedEvents.length > 0">
                        <div>
                            <!-- Desktop Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Hora</th>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Torneo & Cancha</th>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Categoría</th>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Encuentro</th>
                                            <th scope="col"
                                                class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <template x-for="item in selectedEvents" :key="item.id">
                                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div
                                                        class="text-sm font-bold text-club-primary bg-blue-50 inline-flex px-3 py-1 rounded-full">
                                                        <i class="bi bi-clock mr-1"></i> <span
                                                            x-text="item.hora"></span></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900" x-text="item.torneo">
                                                    </div>
                                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                                        <i class="bi bi-geo-alt-fill text-indigo-400 mr-1"></i> <span
                                                            x-text="item.cancha"></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800"
                                                        x-text="item.categoriaUno + (item.categoriaDos ? ' / ' + item.categoriaDos : '')"></span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-2 text-sm font-semibold">
                                                        <span class="text-gray-900" x-text="item.eLocal"></span>
                                                        <span
                                                            class="text-gray-400 text-xs px-2 py-0.5 bg-gray-100 rounded">VS</span>
                                                        <span class="text-red-600 font-bold"
                                                            x-text="item.eVisitante"></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <button @click="openShow(item)" title="Ver Detalles"
                                                            class="p-2 text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors focus:outline-none">
                                                            <i class="bi bi-eye text-lg"></i>
                                                        </button>

                                                        @hasanyrole('Admin|Profesor')
                                                        <button @click="openEdit(item)" title="Editar"
                                                            class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors focus:outline-none">
                                                            <i class="bi bi-pencil-square text-lg"></i>
                                                        </button>
                                                        @endhasanyrole

                                                        @hasanyrole('Admin|Profesor')
                                                        <button @click="openPayments(item)" title="Control de Pagos"
                                                            class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors focus:outline-none">
                                                            <i class="bi bi-cash-coin text-lg"></i>
                                                        </button>
                                                        @endhasanyrole

                                                        @hasrole('Admin')
                                                        <button @click="openDelete(item)" title="Eliminar"
                                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors focus:outline-none">
                                                            <i class="bi bi-trash text-lg"></i>
                                                        </button>
                                                        @endrole
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards -->
                            <div class="md:hidden divide-y divide-gray-100">
                                <template x-for="item in selectedEvents" :key="item.id">
                                    <div class="p-4 space-y-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center space-x-2">
                                                <div class="bg-club-primary text-white p-2 rounded-xl shadow-sm">
                                                    <i class="bi bi-clock-fill"></i>
                                                </div>
                                                <div class="text-lg font-black text-club-primary" x-text="item.hora">
                                                </div>
                                            </div>
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded-lg bg-club-secondary text-gray-900 border border-club-secondary/30"
                                                x-text="item.categoriaUno + (item.categoriaDos ? ' / ' + item.categoriaDos : '')"></span>
                                        </div>

                                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                                                x-text="item.torneo"></div>
                                            <div class="flex items-center space-x-3 justify-center py-2">
                                                <span class="text-sm font-black text-gray-900"
                                                    x-text="item.eLocal"></span>
                                                <span
                                                    class="text-[10px] font-black bg-white px-2 py-1 rounded-lg border border-gray-100 shadow-sm text-gray-400">VS</span>
                                                <span class="text-sm font-black text-red-600"
                                                    x-text="item.eVisitante"></span>
                                            </div>
                                            <div
                                                class="mt-2 flex items-center justify-center text-[10px] font-bold text-gray-500 bg-white/50 rounded-full py-1">
                                                <i class="bi bi-geo-alt-fill mr-1 text-club-primary"></i> <span
                                                    x-text="item.cancha"></span>
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <button @click="openShow(item)"
                                                class="flex-1 flex items-center justify-center py-2.5 bg-blue-50 text-club-primary rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
                                                <i class="bi bi-eye mr-2"></i> Detalles
                                            </button>
                                            @hasanyrole('Admin|Profesor')
                                            <button @click="openEdit(item)"
                                                class="flex-1 flex items-center justify-center py-2.5 bg-amber-50 text-amber-600 rounded-xl font-bold text-xs uppercase tracking-widest transition-all border border-amber-100">
                                                <i class="bi bi-pencil mr-2"></i> Editar
                                            </button>
                                            @endhasanyrole
                                            @hasrole('Admin')
                                            <button @click="openDelete(item)"
                                                class="p-2.5 bg-red-50 text-red-500 rounded-xl transition-all border border-red-100">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                            @endrole
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- CREATE MODAL -->
        <div x-show="openCreateModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openCreateModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    @click="openCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openCreateModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">

                    <form action="{{ route('programming.store') }}" method="post" class="requires-validation" novalidate
                        @submit="submitting = true">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Agregar Nueva
                                    Programación</h3>
                                <button type="button" @click="openCreateModal = false"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="bi bi-x-lg text-xl"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar Torneo
                                        (Opcional)</label>
                                    <select name="tournament_id" x-model="selectedTournamentId"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                        <option value="">-- No asociado --</option>
                                        @foreach($tournaments as $tour)
                                            <option value="{{ $tour->id }}">{{ $tour->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción del
                                        Encuentro <span class="text-red-500">*</span></label>
                                    <input type="text" name="torneo" x-model="eventName"
                                        placeholder="Ej: Fecha 1 / Amistoso" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cancha <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="cancha" x-model="selectedCourt"
                                        placeholder="Lugar del partido" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría 1 <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="categoriaUno" placeholder="Ej: 2005"
                                        value="{{ old('categoriaUno') }}" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría 2</label>
                                    <input type="text" name="categoriaDos" placeholder="Opcional"
                                        value="{{ old('categoriaDos') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="fecha" x-model="selectedDateInput"
                                        min="{{now()->format('Y-m-d')}}" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hora <span
                                            class="text-red-500">*</span></label>
                                    <input type="time" name="hora" x-model="selectedTime" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all"
                                        :class="hasConflict ? 'border-red-500 ring-red-200' : ''">
                                    <p x-show="hasConflict"
                                        class="text-[10px] text-red-600 font-bold mt-1 animate-bounce">
                                        <i class="bi bi-exclamation-triangle-fill"></i> ¡Conflicto! Ya hay un partido a
                                        las <span x-text="conflictInfo"></span> en esta cancha.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo Local <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="eLocal" value="{{ old('eLocal', 'Jackeline FS') }}"
                                        required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo Visitante <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="eVisitante" placeholder="Rival"
                                        value="{{ old('eVisitante') }}" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                                </div>

                                <div
                                    class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 col-span-1 md:col-span-2 lg:col-span-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div x-show="!selectedTournament">
                                            <label class="block text-sm font-bold text-indigo-900 mb-1"><i
                                                    class="bi bi-cash-stack mr-1"></i> Inscripción ($ por
                                                deportista)</label>
                                            <input type="number" name="costo_inscripcion" value="0"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all font-black">
                                        </div>
                                        <div x-show="selectedTournament" class="flex flex-col justify-center">
                                            <div class="p-3 bg-white/50 rounded-xl border border-indigo-100/50">
                                                <p
                                                    class="text-[10px] text-indigo-600 font-black uppercase tracking-widest leading-tight">
                                                    <i class="bi bi-info-circle-fill mr-1"></i> Inscripción Protegida
                                                </p>
                                                <p class="text-[9px] text-indigo-500 font-medium mt-1">El costo de
                                                    inscripción se gestiona desde el panel del torneo.</p>
                                                <input type="hidden" name="costo_inscripcion" value="0">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-indigo-900 mb-1"><i
                                                    class="bi bi-person-badge-fill mr-1"></i> Arbitraje ($ por
                                                deportista)</label>
                                            <input type="number" name="costo_arbitraje" :value="calculatedArbitr"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all font-black">
                                        </div>
                                    </div>

                                    <template x-if="selected.length > 0 && selectedTournament">
                                        <div
                                            class="mt-3 p-2 bg-white/60 rounded-lg border border-indigo-100 text-[10px] font-bold text-indigo-700 flex justify-between items-center animate-pulse">
                                            <span><i class="bi bi-calculator mr-1"></i> Sugerido según Torneo (<span
                                                    x-text="selectedTournament ? selectedTournament.students.length : selected.length"></span> jugadores):</span>
                                            <div class="flex gap-4">
                                                <span x-show="selectedTournament.costo_total_inscripcion > 0">Inscrip:
                                                    $<span
                                                        x-text="calculatedInscrip"></span></span>
                                                <span x-show="selectedTournament.costo_total_arbitraje > 0">Arbitr:
                                                    $<span
                                                        x-text="calculatedArbitr"></span></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Lista de jugadores convocados (Transfer List) -->
                                <div class="col-span-1 md:col-span-2 lg:col-span-3 w-full"
                                    x-init="available = [...allStudents]">
                                    <div x-show="selectedTournament"
                                        class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-4 flex items-start space-x-4 shadow-sm">
                                        <div class="bg-indigo-100 p-3 rounded-xl">
                                            <i class="bi bi-people-fill text-indigo-600 text-2xl"></i>
                                        </div>
                                        <div>
                                            <h5
                                                class="text-indigo-900 font-black text-sm uppercase tracking-wider mb-1">
                                                Planilla Automática Activada</h5>
                                            <p class="text-indigo-700 text-sm leading-relaxed">
                                                Este partido está vinculado al torneo <strong
                                                    x-text="selectedTournament ? selectedTournament.name : ''"></strong>.
                                                El sistema usará automáticamente los <strong
                                                    x-text="selectedTournament ? selectedTournament.students.length : 0"></strong>
                                                deportistas que asociaste a este torneo en la planilla oficial.
                                            </p>
                                        </div>
                                    </div>

                                    <div x-show="!selectedTournament">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Seleccionar
                                            Jugadores Convocados <span class="text-red-500">*</span></label>

                                        <template x-for="player in selected" :key="player.id">
                                            <input type="hidden" name="jugadores_convocados[]" :value="player.id">
                                        </template>

                                        <div class="w-full flex flex-col md:flex-row gap-4 items-stretch">
                                            <!-- Available -->
                                            <div
                                                class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                                <div
                                                    class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                                    <span class="font-bold text-sm text-gray-700">
                                                        Disponibles <span
                                                            class="font-normal text-xs text-gray-500">(Mostrando <span
                                                                x-text="filteredAvailable.length"></span> de <span
                                                                x-text="available.length"></span>)</span>
                                                    </span>
                                                    <button type="button" @click="moveAllToSelected"
                                                        class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Convocar
                                                        Visibles <i class="bi bi-chevron-double-right"></i></button>
                                                </div>
                                                <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                                                    <div class="relative">
                                                        <i
                                                            class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                                        <input type="text" x-model="searchLeft"
                                                            placeholder="Buscar nombre o año..."
                                                            class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-gray-50/30">
                                                    <template x-for="player in filteredAvailable" :key="player.id">
                                                        <div @click="moveToSelected(player)"
                                                            class="flex justify-between items-center p-2 rounded-lg hover:bg-indigo-50 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors group">
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-800 group-hover:text-indigo-700"
                                                                    x-text="player.name"></div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <span
                                                                    class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-200 text-gray-600 group-hover:bg-indigo-200 group-hover:text-indigo-800"
                                                                    x-text="player.category"></span>
                                                                <i
                                                                    class="bi bi-arrow-right-short text-gray-300 group-hover:text-indigo-500 text-lg"></i>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="filteredAvailable.length === 0"
                                                        class="text-center py-4 text-sm text-gray-400 italic">No hay
                                                        jugadores</div>
                                                </div>
                                            </div>

                                            <!-- Selected -->
                                            <div
                                                class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                                <div
                                                    class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                                                    <span class="font-bold text-sm text-indigo-900">Convocados (<span
                                                            x-text="selected.length"></span>)</span>
                                                    <button type="button" @click="moveAllToAvailable"
                                                        class="text-xs text-red-600 hover:text-red-800 font-semibold"><i
                                                            class="bi bi-chevron-double-left"></i> Quitar Todos</button>
                                                </div>
                                                <div class="p-2 border-b border-indigo-50 bg-indigo-50/30">
                                                    <div class="relative">
                                                        <i
                                                            class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                                        <input type="text" x-model="searchRight"
                                                            placeholder="Buscar convocado..."
                                                            class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-white">
                                                    <template x-for="player in filteredSelected" :key="player.id">
                                                        <div @click="moveToAvailable(player)"
                                                            class="flex justify-between items-center p-2 rounded-lg bg-green-50 hover:bg-red-50 cursor-pointer border border-green-100 hover:border-red-100 transition-colors group">
                                                            <div class="flex items-center space-x-2">
                                                                <i
                                                                    class="bi bi-arrow-left-short text-transparent group-hover:text-red-500 text-lg transition-colors"></i>
                                                                <div class="text-sm font-bold text-green-800 group-hover:text-red-700"
                                                                    x-text="player.name"></div>
                                                            </div>
                                                            <span
                                                                class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-200 text-green-800 group-hover:bg-red-200 group-hover:text-red-800"
                                                                x-text="player.category"></span>
                                                        </div>
                                                    </template>
                                                    <div x-show="selected.length === 0"
                                                        class="flex flex-col items-center justify-center h-full text-center p-4">
                                                        <p class="text-sm text-gray-400">Haz clic en un jugador para
                                                            convocarlo.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="checkbox" x-show="!selectedTournament"
                                            :checked="selected.length > 0" :required="!selectedTournament"
                                            class="opacity-0 absolute -z-10"
                                            oninvalid="this.setCustomValidity('Debes convocar al menos un jugador')"
                                            oninput="this.setCustomValidity('')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                            <button type="submit" :disabled="submitting"
                                class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="!submitting">
                                    <i class="bi bi-save mr-2"></i>
                                </template>
                                <template x-if="submitting">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </template>
                                <span x-text="submitting ? 'Guardando...' : 'Guardar Programación'"></span>
                            </button>
                            <button type="button" @click="openCreateModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div x-show="openEditModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openEditModal = false">
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">

                    <form :action="'{{ route('programming.update', 999999) }}'.replace('999999', editingItem.id)"
                        method="post" class="requires-validation" @submit="submitting = true">
                        @method('put')
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl leading-6 font-bold text-gray-900">Actualizar Programación</h3>
                                <button type="button" @click="openEditModal = false"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="bi bi-x-lg text-xl"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Torneo
                                        Asociado</label>
                                    <select name="tournament_id" x-model="selectedTournamentIdEdit"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                        <option value="">-- No asociado --</option>
                                        @foreach($tournaments as $tour)
                                            <option value="{{ $tour->id }}">{{ $tour->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción del
                                        Encuentro</label>
                                    <input type="text" name="torneo" x-model="eventNameEdit" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría 1</label>
                                    <input type="text" name="categoriaUno" :value="editingItem.categoriaUno" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cancha</label>
                                    <input type="text" name="cancha" x-model="selectedCourtEdit" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo Local</label>
                                    <input type="text" name="eLocal" :value="editingItem.eLocal" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo
                                        Visitante</label>
                                    <input type="text" name="eVisitante" :value="editingItem.eVisitante" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                                    <input type="date" name="fecha" x-model="selectedDateEdit" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hora</label>
                                    <input type="time" name="hora" x-model="selectedTimeEdit" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                                        :class="hasConflictEdit ? 'border-red-500 ring-red-200' : ''">
                                    <p x-show="hasConflictEdit" class="text-[10px] text-red-600 font-bold mt-1">
                                        <i class="bi bi-exclamation-triangle-fill"></i> ¡Conflicto detected!
                                    </p>
                                </div>

                                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 md:col-span-2">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div x-show="!selectedTournamentEdit">
                                            <label class="block text-sm font-bold text-indigo-900 mb-1">Inscrip.
                                                (Indiv.)</label>
                                            <input type="number" name="costo_inscripcion"
                                                :value="editingItem.costo_inscripcion"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all font-black">
                                        </div>
                                        <div x-show="selectedTournamentEdit" class="flex flex-col justify-center">
                                            <div class="p-3 bg-white/50 rounded-xl border border-indigo-100/50">
                                                <p
                                                    class="text-[10px] text-indigo-600 font-black uppercase tracking-widest leading-tight">
                                                    <i class="bi bi-info-circle-fill mr-1"></i> Inscripción de Torneo
                                                </p>
                                                <p class="text-[9px] text-indigo-500 font-medium mt-1">Costo
                                                    centralizado en el módulo de torneos.</p>
                                                <input type="hidden" name="costo_inscripcion" value="0">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-indigo-900 mb-1">Arbitr.
                                                (Indiv.)</label>
                                            <input type="number" name="costo_arbitraje" :value="calculatedArbitrEdit"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all font-black">
                                        </div>
                                    </div>

                                    <template x-if="editSelected.length > 0 && selectedTournamentEdit">
                                        <div
                                            class="mt-3 p-2 bg-white/60 rounded-lg border border-indigo-100 text-[10px] font-bold text-indigo-700 flex justify-between items-center animate-pulse">
                                            <span><i class="bi bi-calculator mr-1"></i> Sugerido según Torneo (<span
                                                    x-text="selectedTournamentEdit ? selectedTournamentEdit.students.length : editSelected.length"></span> jugadores):</span>
                                            <div class="flex gap-4">
                                                <span
                                                    x-show="selectedTournamentEdit.costo_total_inscripcion > 0">Inscrip:
                                                    $<span
                                                        x-text="calculatedInscripEdit"></span></span>
                                                <span x-show="selectedTournamentEdit.costo_total_arbitraje > 0">Arbitr:
                                                    $<span
                                                        x-text="calculatedArbitrEdit"></span></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Edit Transfer List -->
                                <div class="col-span-1 md:col-span-2 lg:col-span-3 w-full">
                                    <div x-show="selectedTournamentEdit"
                                        class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-4 flex items-start space-x-4 shadow-sm"
                                        style="display: none;">
                                        <div class="bg-indigo-100 p-3 rounded-xl">
                                            <i class="bi bi-people-fill text-indigo-600 text-2xl"></i>
                                        </div>
                                        <div>
                                            <h5
                                                class="text-indigo-900 font-black text-sm uppercase tracking-wider mb-1">
                                                Planilla Automática Activada</h5>
                                            <p class="text-indigo-700 text-sm leading-relaxed">
                                                Este partido está vinculado al torneo <strong
                                                    x-text="selectedTournamentEdit ? selectedTournamentEdit.name : ''"></strong>.
                                                El sistema mantendrá sincronizados a los <strong
                                                    x-text="selectedTournamentEdit ? (selectedTournamentEdit.students ? selectedTournamentEdit.students.length : 0) : 0"></strong>
                                                deportistas de la planilla oficial del torneo.
                                            </p>
                                        </div>
                                    </div>

                                    <div x-show="!selectedTournamentEdit">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Editar Jugadores
                                            Convocados</label>

                                        <template x-for="player in editSelected" :key="player.id">
                                            <input type="hidden" name="jugadores_convocados[]" :value="player.id">
                                        </template>

                                        <div class="flex flex-col md:flex-row gap-4 items-stretch">
                                            <!-- Available -->
                                            <div
                                                class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                                <div
                                                    class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                                    <span class="font-bold text-sm text-gray-700">Disponibles <span
                                                            class="font-normal text-xs text-gray-500">(<span
                                                                x-text="editFilteredAvailable.length"></span> de <span
                                                                x-text="editAvailable.length"></span>)</span></span>
                                                    <button type="button" @click="editMoveAllToSelected"
                                                        class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Convocar
                                                        Visibles <i class="bi bi-chevron-double-right"></i></button>
                                                </div>
                                                <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                                                    <div class="relative">
                                                        <i
                                                            class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                                        <input type="text" x-model="editSearchLeft"
                                                            placeholder="Buscar..."
                                                            class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500">
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-gray-50/30">
                                                    <template x-for="player in editFilteredAvailable" :key="player.id">
                                                        <div @click="editMoveToSelected(player)"
                                                            class="flex justify-between items-center p-2 rounded-lg hover:bg-indigo-50 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors group">
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-800 group-hover:text-indigo-700"
                                                                    x-text="player.name"></div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <span
                                                                    class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-200 text-gray-600"
                                                                    x-text="player.category"></span>
                                                                <i
                                                                    class="bi bi-arrow-right-short text-gray-300 group-hover:text-indigo-500 text-lg"></i>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="editFilteredAvailable.length === 0"
                                                        class="text-center py-4 text-sm text-gray-400 italic">No hay
                                                        jugadores</div>
                                                </div>
                                            </div>

                                            <!-- Selected -->
                                            <div
                                                class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                                <div
                                                    class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                                                    <span class="font-bold text-sm text-indigo-900">Convocados (<span
                                                            x-text="editSelected.length"></span>)</span>
                                                    <button type="button" @click="editMoveAllToAvailable"
                                                        class="text-xs text-red-600 hover:text-red-800 font-semibold"><i
                                                            class="bi bi-chevron-double-left"></i> Quitar Todos</button>
                                                </div>
                                                <div class="p-2 border-b border-indigo-50 bg-indigo-50/30">
                                                    <div class="relative">
                                                        <i
                                                            class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                                        <input type="text" x-model="editSearchRight"
                                                            placeholder="Buscar..."
                                                            class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500">
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-white">
                                                    <template x-for="player in editFilteredSelected" :key="player.id">
                                                        <div @click="editMoveToAvailable(player)"
                                                            class="flex justify-between items-center p-2 rounded-lg bg-green-50 hover:bg-red-50 cursor-pointer border border-green-100 hover:border-red-100 transition-colors group">
                                                            <div class="flex items-center space-x-2">
                                                                <i
                                                                    class="bi bi-arrow-left-short text-transparent group-hover:text-red-500 text-lg"></i>
                                                                <div class="text-sm font-bold text-green-800 group-hover:text-red-700"
                                                                    x-text="player.name"></div>
                                                            </div>
                                                            <span
                                                                class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-200 text-green-800"
                                                                x-text="player.category"></span>
                                                        </div>
                                                    </template>
                                                    <div x-show="editSelected.length === 0"
                                                        class="flex flex-col items-center justify-center h-full text-center p-4">
                                                        <p class="text-sm text-gray-400">Haz clic en un jugador para
                                                            convocarlo.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div
                            class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                            <button type="submit" :disabled="submitting"
                                class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-amber-500 text-base font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="submitting">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </template>
                                <span x-text="submitting ? 'Actualizando...' : 'Guardar Cambios'"></span>
                            </button>
                            <button type="button" @click="openEditModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div x-show="openDeleteModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openDeleteModal = false">
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Eliminar Programación</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        ¿Estás seguro que deseas eliminar la programación para el torneo <strong
                                            x-text="deletingItem.torneo ? deletingItem.torneo.toUpperCase() : ''"></strong>?
                                        Esta acción no se puede deshacer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                        <form :action="'{{ route('programming.destroy', 999999) }}'.replace('999999', deletingItem.id)"
                            method="post" class="m-0" @submit="submitting = true">
                            @csrf
                            @method('delete')
                            <button type="submit" :disabled="submitting"
                                class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-6 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="submitting">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </template>
                                <span x-text="submitting ? 'Eliminando...' : 'Sí, Eliminar'"></span>
                            </button>
                        </form>
                        <button type="button" @click="openDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SHOW MODAL -->
        <div x-show="openShowModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openShowModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openShowModal = false">
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="openShowModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">

                    <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="bi bi-info-circle mr-2"></i> Detalles del Encuentro
                        </h3>
                        <button @click="openShowModal = false"
                            class="text-indigo-200 hover:text-white transition-colors">
                            <i class="bi bi-x-circle-fill text-2xl"></i>
                        </button>
                    </div>

                    <div class="bg-white px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                            <!-- Detalles -->
                            <div class="md:col-span-5 space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Torneo</p>
                                    <p class="text-lg font-bold text-gray-900" x-text="showingItem.torneo"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Encuentro</p>
                                    <div class="flex items-center space-x-2 text-base font-semibold mt-1">
                                        <span class="text-gray-900" x-text="showingItem.eLocal"></span>
                                        <span class="text-gray-400 text-xs px-2 py-0.5 bg-gray-100 rounded">VS</span>
                                        <span class="text-red-600 font-bold" x-text="showingItem.eVisitante"></span>
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-gray-100 pt-3">
                                    <div>
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Fecha</p>
                                        <p class="font-semibold text-gray-800"><i
                                                class="bi bi-calendar3 mr-1 text-indigo-400"></i> <span
                                                x-text="showingItem.fecha"></span></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Hora</p>
                                        <p class="font-semibold text-gray-800"><i
                                                class="bi bi-clock mr-1 text-indigo-400"></i> <span
                                                x-text="showingItem.hora"></span></p>
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-gray-100 pt-3">
                                    <div>
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Categoría
                                        </p>
                                        <span
                                            class="inline-block mt-1 px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800"
                                            x-text="showingItem.categoriaUno + (showingItem.categoriaDos ? ' / ' + showingItem.categoriaDos : '')"></span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Cancha</p>
                                        <p class="font-semibold text-gray-800 mt-1"><i
                                                class="bi bi-geo-alt-fill mr-1 text-indigo-400"></i> <span
                                                x-text="showingItem.cancha"></span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Convocados -->
                            <div class="md:col-span-7 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                                <p
                                    class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mb-4 flex items-center">
                                    <i class="bi bi-people-fill mr-2 text-indigo-500"></i> Jugadores Convocados (<span
                                        x-text="showingPlayers.length"></span>)
                                </p>
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                    <template x-for="(player, index) in showingPlayers" :key="index">
                                        <div
                                            class="flex items-center text-xs font-bold text-gray-700 bg-white px-3 py-2.5 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-200 transition-colors">
                                            <div class="w-2 h-2 rounded-full bg-green-400 mr-3 animate-pulse"></div>
                                            <span x-text="player" class="truncate"></span>
                                        </div>
                                    </template>
                                    <template x-if="showingPlayers.length === 0">
                                        <div class="col-span-full text-sm text-gray-400 italic text-center py-8">
                                            <i class="bi bi-person-x text-2xl block mb-2 opacity-20"></i>
                                            No hay jugadores convocados
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end rounded-b-2xl border-t border-gray-100">
                        <button type="button" @click="openShowModal = false"
                            class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAYMENTS MODAL -->
        <div x-show="openPaymentsModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openPaymentsModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="openPaymentsModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="openPaymentsModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">

                    <div class="bg-green-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="bi bi-cash-coin mr-2"></i> Control de Pagos - <span x-text="paymentItem.torneo"
                                class="ml-1"></span>
                        </h3>
                        <button @click="openPaymentsModal = false"
                            class="text-green-200 hover:text-white transition-colors">
                            <i class="bi bi-x-circle-fill text-2xl"></i>
                        </button>
                    </div>

                    <div class="bg-white px-6 py-6">
                        <div
                            class="mb-4 flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Inscrip. (Indiv.)
                                </p>
                                <p class="text-lg font-black text-gray-900"
                                    x-text="'$' + (paymentItem.costo_inscripcion || 0)"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Arbitr. (Indiv.)</p>
                                <p class="text-lg font-black text-gray-900"
                                    x-text="'$' + (paymentItem.costo_arbitraje || 0)"></p>
                            </div>
                        </div>

                        <div class="overflow-hidden border border-gray-100 rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                            Deportista</th>
                                        <th
                                            class="px-4 py-3 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                            Inscrip.</th>
                                        <th
                                            class="px-4 py-3 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                            Arbitr.</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <template x-for="p in paymentList" :key="p.student_id">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-700"
                                                x-text="p.name"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-1">
                                                    <span class="text-[10px] text-gray-400">$</span>
                                                    <input type="number" x-model="p.pagado_inscripcion"
                                                        class="w-20 text-xs font-bold rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 py-1 px-2 transition-all">
                                                    <button type="button"
                                                        @click="p.pagado_inscripcion = suggestedInscripForPayment"
                                                        title="Pagar todo"
                                                        class="p-1 text-green-600 hover:bg-green-50 rounded transition-colors">
                                                        <i class="bi bi-check-all text-lg"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-1">
                                                    <span class="text-[10px] text-gray-400">$</span>
                                                    <input type="number" x-model="p.pagado_arbitraje"
                                                        class="w-20 text-xs font-bold rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 py-1 px-2 transition-all">
                                                    <button type="button"
                                                        @click="p.pagado_arbitraje = paymentItem.costo_arbitraje"
                                                        title="Pagar todo"
                                                        class="p-1 text-green-600 hover:bg-green-50 rounded transition-colors">
                                                        <i class="bi bi-check-all text-lg"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div
                        class="bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-2xl border-t border-gray-100">
                        <span class="text-[10px] text-gray-400 font-bold italic">* Los cambios se guardan al presionar
                            Guardar</span>
                        <div class="flex space-x-3">
                            <button type="button" @click="openPaymentsModal = false"
                                class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors">
                                Cancelar
                            </button>
                            <button type="button" @click="savePayments"
                                class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2 bg-green-600 text-base font-bold text-white hover:bg-green-700 focus:outline-none transition-all transform hover:scale-105">
                                <i class="bi bi-save mr-2"></i> Guardar Pagos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calendarApp(tournaments = []) {
            return {
                tournaments: tournaments,
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                events: {!! $eventsByDate !!},
                selectedDate: null,
                selectedEvents: [],
                printing: false,
                submitting: false,

                allStudents: [
                    @foreach($studentList ?? [] as $student)
                        { id: {{ $student->id }}, name: '{{ addslashes($student->nomDeportista) }}', category: '{{ $student->Categoria }}' },
                    @endforeach
            ],

                openCreateModal: false,
                openEditModal: false,
                openDeleteModal: false,
                openShowModal: false,
                openPaymentsModal: false,
                editingItem: {},
                deletingItem: {},
                showingItem: {},
                paymentItem: {},
                paymentList: [],
                showingPlayers: [],

                // Create Modal Transfer List
                selectedTournamentId: '',
                available: [],
                selected: [],
                searchLeft: '',
                searchRight: '',

                // Edit Modal Transfer List
                selectedTournamentIdEdit: '',
                editSearchLeft: '',
                editSearchRight: '',
                editAvailable: [],
                editSelected: [],
                eventName: '',
                eventNameEdit: '',
                selectedTime: '',
                selectedDateInput: '',
                selectedCourt: '',
                selectedTimeEdit: '',
                selectedDateEdit: '',
                selectedCourtEdit: '',

                get whatsappDailyUrl() {
                    if (this.selectedEvents.length === 0) return '#';

                    // Sort events by time
                    const sortedEvents = [...this.selectedEvents].sort((a, b) => a.hora.localeCompare(b.hora));

                    let message = '⚽ *PROGRAMACIÓN JACKELINE FS*\n';
                    message += '📅 *Fecha:* ' + this.formatDateHuman(this.selectedDate) + '\n';
                    message += '━━━━━━━━━━━━━━━━\n\n';

                    sortedEvents.forEach((item) => {
                        message += '⏰ *' + item.hora + '* | 🏟️ ' + item.cancha + '\n';
                        message += '🏆 ' + item.torneo + '\n';
                        message += '👥 ' + item.categoriaUno + (item.categoriaDos ? ' / ' + item.categoriaDos : '') + '\n';
                        message += '⚔️ *' + item.eLocal + '* VS *' + item.eVisitante + '*\n';
                        message += '━━━━━━━━━━━━━━━━\n';
                    });

                    message += '\n¡Vamos con toda! 🔥';

                    return 'https://wa.me/?text=' + encodeURIComponent(message);
                },

                init() {
                    this.available = [...this.allStudents];

                    // Auto-fill event name on tournament selection (Create)
                    this.$watch('selectedTournamentId', (value) => {
                        if (value && this.selectedTournament) {
                            this.eventName = this.selectedTournament.name;
                        }
                    });

                    // Auto-fill event name on tournament selection (Edit)
                    this.$watch('selectedTournamentIdEdit', (value) => {
                        if (value && this.selectedTournamentEdit) {
                            this.eventNameEdit = this.selectedTournamentEdit.name;
                        }
                    });
                },

                get suggestedInscripForPayment() {
                    if (!this.paymentItem) return 0;
                    const tourId = this.paymentItem.tournament_id;
                    if (!tourId) return this.paymentItem.costo_inscripcion;

                    const tour = this.tournaments.find(t => t.id == tourId);
                    if (!tour) return this.paymentItem.costo_inscripcion;

                    const count = (tour.students && tour.students.length > 0) ? tour.students.length : 1;
                    return Math.round(tour.costo_total_inscripcion / count);
                },

                get hasConflict() {
                    if (!this.selectedTime || !this.selectedDateInput || !this.selectedCourt) return false;
                    return this.checkTimeOverlap(this.selectedDateInput, this.selectedTime, this.selectedCourt);
                },

                get hasConflictEdit() {
                    if (!this.selectedTimeEdit || !this.selectedDateEdit || !this.selectedCourtEdit) return false;
                    return this.checkTimeOverlap(this.selectedDateEdit, this.selectedTimeEdit, this.selectedCourtEdit, this.editingItem.id);
                },

                get conflictInfo() {
                    const conflict = this.hasConflict;
                    return conflict ? conflict.hora : '';
                },

                checkTimeOverlap(date, time, court, excludeId = null) {
                    const dayEvents = this.events[date] || [];
                    const newStart = this.timeToMinutes(time);
                    const newEnd = newStart + 59;

                    for (let event of dayEvents) {
                        if (excludeId && event.id == excludeId) continue;
                        if (event.cancha !== court) continue;

                        const existingStart = this.timeToMinutes(event.hora);
                        const existingEnd = existingStart + 59;

                        if (newStart <= existingEnd && newEnd >= existingStart) {
                            return event;
                        }
                    }
                    return false;
                },

                timeToMinutes(timeStr) {
                    if (!timeStr) return 0;
                    const [h, m] = timeStr.split(':').map(Number);
                    return h * 60 + m;
                },

                get calculatedInscrip() {
                    const tour = this.selectedTournament;
                    const count = tour ? tour.students.length : this.selected.length;
                    if (!tour || count === 0) return 0;
                    return Math.round(tour.costo_total_inscripcion / count);
                },

                get calculatedArbitr() {
                    const tour = this.selectedTournament;
                    const count = tour ? tour.students.length : this.selected.length;
                    if (!tour || count === 0) return 0;
                    return Math.round(tour.costo_total_arbitraje / count);
                },

                get calculatedInscripEdit() {
                    const tour = this.selectedTournamentEdit;
                    if (!tour || (!tour.costo_total_inscripcion && this.editingItem.costo_inscripcion > 0)) {
                        return this.editingItem.costo_inscripcion;
                    }
                    const count = (tour.students && tour.students.length > 0) ? tour.students.length : (this.editSelected.length || 1);
                    return Math.round((tour.costo_total_inscripcion || 0) / count);
                },

                get calculatedArbitrEdit() {
                    const tour = this.selectedTournamentEdit;
                    if (!tour || (!tour.costo_total_arbitraje && this.editingItem.costo_arbitraje > 0)) {
                        return this.editingItem.costo_arbitraje;
                    }
                    const count = (tour.students && tour.students.length > 0) ? tour.students.length : (this.editSelected.length || 1);
                    return Math.round((tour.costo_total_arbitraje || 0) / count);
                },

                get selectedTournament() {
                    if (!this.selectedTournamentId) return null;
                    return this.tournaments.find(t => t.id == this.selectedTournamentId);
                },

                get selectedTournamentEdit() {
                    if (!this.selectedTournamentIdEdit) return null;
                    return this.tournaments.find(t => t.id == this.selectedTournamentIdEdit);
                },

                get daysInMonth() {
                    return new Date(this.year, this.month + 1, 0).getDate();
                },
                get blankDays() {
                    // Get the day of the week the month starts on (0 is Sunday, 1 is Monday)
                    let firstDay = new Date(this.year, this.month, 1).getDay();
                    // Adjust to make Monday the first day of the week
                    let blanks = firstDay === 0 ? 6 : firstDay - 1;
                    return Array.from({ length: blanks });
                },
                get endBlankDays() {
                    // Get total cells used
                    let totalCells = this.blankDays.length + this.daysInMonth;
                    // Calculate remaining cells to complete the grid (multiples of 7)
                    let remaining = 7 - (totalCells % 7);
                    if (remaining === 7) return [];
                    return Array.from({ length: remaining });
                },
                get days() {
                    return Array.from({ length: this.daysInMonth }, (_, i) => i + 1);
                },
                formatDate(day) {
                    let yyyy = this.year;
                    let mm = String(this.month + 1).padStart(2, '0');
                    let dd = String(day).padStart(2, '0');
                    return `${yyyy}-${mm}-${dd}`;
                },
                formatDateHuman(dateStr) {
                    if (!dateStr) return '';
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    // Using T00:00:00 to avoid timezone offset issues
                    return new Date(dateStr + "T00:00:00").toLocaleDateString('es-ES', options);
                },
                isToday(day) {
                    const today = new Date();
                    return this.formatDate(day) === `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                },
                isSelected(day) {
                    return this.formatDate(day) === this.selectedDate;
                },
                hasEvents(day) {
                    return this.events[this.formatDate(day)] !== undefined;
                },
                getEventsForDay(day) {
                    return this.events[this.formatDate(day)] || [];
                },
                getEventsCount(day) {
                    return this.getEventsForDay(day).length;
                },
                selectDay(day) {
                    this.selectedDate = this.formatDate(day);
                    this.selectedEvents = this.getEventsForDay(day);
                    // Quick scroll to details
                    setTimeout(() => {
                        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    }, 100);
                },
                prevMonth() {
                    if (this.month === 0) {
                        this.month = 11;
                        this.year--;
                    } else {
                        this.month--;
                    }
                    this.selectedDate = null;
                },
                nextMonth() {
                    if (this.month === 11) {
                        this.month = 0;
                        this.year++;
                    } else {
                        this.month++;
                    }
                    this.selectedDate = null;
                },
                goToToday() {
                    this.month = new Date().getMonth();
                    this.year = new Date().getFullYear();
                    this.selectDay(new Date().getDate());
                },

                // Parser robusto para jugadores (maneja arrays JSON viejos o comas simples)
                parseConvocados(raw) {
                    if (!raw) return [];
                    let parsed = [];
                    try {
                        if (raw.trim().startsWith('[')) {
                            let json = JSON.parse(raw);
                            parsed = Array.isArray(json) ? json : raw.split(',');
                        } else {
                            parsed = raw.split(',');
                        }
                    } catch (e) {
                        parsed = raw.split(',');
                    }
                    // Limpiar comillas, corchetes y espacios en blanco residuales
                    return parsed.map(p => p.toString().replace(/[\[\]"]/g, '').trim()).filter(p => p !== '');
                },

                // Show Modal Logic
                openShow(item) {
                    this.showingItem = item;
                    let convocadosArray = this.parseConvocados(item.jugadores_convocados);
                    // Si son IDs, buscar nombres. Si son nombres (viejos), dejarlos.
                    this.showingPlayers = convocadosArray.map(p => {
                        let student = this.allStudents.find(s => s.id == p);
                        return student ? student.name : p;
                    });
                    this.openShowModal = true;
                },

                // Edit Modal Logic
                openEdit(item) {
                    this.editingItem = item;
                    this.selectedTournamentIdEdit = item.tournament_id || '';

                    let convocadosArray = this.parseConvocados(item.jugadores_convocados);
                    // Intentar filtrar por ID primero, luego por nombre para retrocompatibilidad
                    this.editSelected = this.allStudents.filter(s => convocadosArray.includes(s.id.toString()) || convocadosArray.includes(s.name));
                    this.editAvailable = this.allStudents.filter(s => !convocadosArray.includes(s.id.toString()) && !convocadosArray.includes(s.name));

                    this.editSearchLeft = '';
                    this.editSearchRight = '';
                    this.eventNameEdit = item.torneo || '';
                    this.selectedTimeEdit = item.hora || '';
                    this.selectedDateEdit = item.fecha || '';
                    this.selectedCourtEdit = item.cancha || '';
                    this.openEditModal = true;
                },
                get editFilteredAvailable() {
                    let filtered = this.editAvailable;

                    // Filter by tournament if selected
                    if (this.selectedTournamentEdit) {
                        const studentIds = this.selectedTournamentEdit.students.map(s => s.id);
                        filtered = filtered.filter(p => studentIds.includes(p.id));
                    }

                    if (this.editSearchLeft !== '') {
                        filtered = filtered.filter(p => p.name.toLowerCase().includes(this.editSearchLeft.toLowerCase()) || p.category.toString().includes(this.editSearchLeft));
                    }
                    return filtered.slice(0, 50);
                },
                get editFilteredSelected() {
                    if (this.editSearchRight === '') return this.editSelected;
                    return this.editSelected.filter(p => p.name.toLowerCase().includes(this.editSearchRight.toLowerCase()) || p.category.toString().includes(this.editSearchRight));
                },
                editMoveToSelected(player) {
                    this.editSelected.push(player);
                    this.editAvailable = this.editAvailable.filter(p => p.id !== player.id);
                },
                editMoveToAvailable(player) {
                    this.editAvailable.push(player);
                    this.editSelected = this.editSelected.filter(p => p.id !== player.id);
                },
                editMoveAllToSelected() {
                    this.editSelected = [...this.editSelected, ...this.editFilteredAvailable];
                    let filteredIds = this.editFilteredAvailable.map(p => p.id);
                    this.editAvailable = this.editAvailable.filter(p => !filteredIds.includes(p.id));
                },
                editMoveAllToAvailable() {
                    this.editAvailable = [...this.editAvailable, ...this.editFilteredSelected];
                    let filteredIds = this.editFilteredSelected.map(p => p.id);
                    this.editSelected = this.editSelected.filter(p => !filteredIds.includes(p.id));
                },

                // Create Helpers
                get filteredAvailable() {
                    let filtered = this.available;

                    // Filter by tournament if selected
                    if (this.selectedTournament) {
                        const studentIds = this.selectedTournament.students.map(s => s.id);
                        filtered = filtered.filter(p => studentIds.includes(p.id));
                    }

                    if (this.searchLeft !== '') {
                        filtered = filtered.filter(p => p.name.toLowerCase().includes(this.searchLeft.toLowerCase()) || p.category.toString().includes(this.searchLeft));
                    }
                    return filtered.slice(0, 50);
                },
                get filteredSelected() {
                    if (this.searchRight === '') return this.selected;
                    return this.selected.filter(p => p.name.toLowerCase().includes(this.searchRight.toLowerCase()) || p.category.toString().includes(this.searchRight));
                },
                moveToSelected(player) {
                    this.selected.push(player);
                    this.available = this.available.filter(p => p.id !== player.id);
                },
                moveToAvailable(player) {
                    this.available.push(player);
                    this.selected = this.selected.filter(p => p.id !== player.id);
                },
                moveAllToSelected() {
                    this.selected = [...this.selected, ...this.filteredAvailable];
                    let filteredIds = this.filteredAvailable.map(p => p.id);
                    this.available = this.available.filter(p => !filteredIds.includes(p.id));
                },
                moveAllToAvailable() {
                    this.available = [...this.available, ...this.filteredSelected];
                    let filteredIds = this.filteredSelected.map(p => p.id);
                    this.selected = this.selected.filter(p => !filteredIds.includes(p.id));
                },

                openDelete(item) {
                    this.deletingItem = item;
                    this.openDeleteModal = true;
                },
                // Payments Logic
                async openPayments(item) {
                    this.paymentItem = item;
                    this.paymentList = [];
                    try {
                        const response = await fetch(`/programming/${item.id}/payments`);
                        this.paymentList = await response.json();
                        this.openPaymentsModal = true;
                    } catch (e) {
                        alert('Error al cargar los pagos: ' + e);
                    }
                },

                async savePayments() {
                    try {
                        const response = await fetch(`/programming/${this.paymentItem.id}/payments`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ payments: this.paymentList })
                        });

                        if (response.ok) {
                            this.openPaymentsModal = false;
                            showToast('Pagos actualizados correctamente', 'success');
                        }
                    } catch (e) {
                        alert('Error al guardar los pagos: ' + e);
                    }
                }
            }
        }
    </script>
</x-app-layout>
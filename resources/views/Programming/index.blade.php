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

  <div class="py-8" x-data="calendarApp()">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
      
      <!-- Top Action Bar -->
      <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <!-- Month Navigation -->
        <div class="flex items-center space-x-4">
            <button @click="prevMonth" class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="bi bi-chevron-left text-lg"></i>
            </button>
            <h3 class="text-xl font-bold text-gray-900 w-48 text-center capitalize" x-text="monthNames[month] + ' ' + year"></h3>
            <button @click="nextMonth" class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="bi bi-chevron-right text-lg"></i>
            </button>
            <button @click="goToToday" class="px-4 py-2 text-sm font-semibold text-club-primary hover:bg-blue-50 rounded-lg transition-colors border border-blue-100">
                Hoy
            </button>
        </div>

        @hasanyrole('Admin|Profesor')
          <button @click="openCreateModal = true" class="inline-flex items-center px-6 py-3 bg-club-primary border border-transparent rounded-xl font-bold text-sm text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-club-primary focus:ring-offset-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <i class="bi bi-plus-circle mr-2 text-lg"></i> {{__('Nueva Programación')}}
          </button>
        @endhasanyrole
      </div>

      <!-- Calendar Grid -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
        <!-- Days of week -->
        <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50/80">
            <template x-for="day in ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']">
                <div class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider" x-text="day"></div>
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
                        <span class="w-7 h-7 flex items-center justify-center rounded-full text-sm font-semibold"
                              :class="{'bg-club-primary text-white': isToday(day), 'text-gray-700': !isToday(day)}">
                            <span x-text="day"></span>
                        </span>
                        
                        <!-- Event Indicator Badge -->
                        <template x-if="hasEvents(day)">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-club-secondary text-gray-900 border border-club-secondary/30">
                                <span x-text="getEventsCount(day)"></span> <i class="bi bi-controller ml-1"></i>
                            </span>
                        </template>
                    </div>

                    <!-- Event Preview -->
                    <div class="mt-2 space-y-1">
                        <template x-for="(event, index) in getEventsForDay(day).slice(0, 2)">
                            <div class="text-[10px] truncate px-1.5 py-1 bg-blue-50 text-blue-700 rounded font-semibold border border-blue-100">
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
      <div x-show="selectedDate" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-gray-100" style="display: none;">
        
        <div class="bg-club-primary px-6 py-4 flex justify-between items-center border-b-4 border-club-secondary">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="bi bi-calendar-check mr-2"></i> 
                Programación para el <span x-text="formatDateHuman(selectedDate)" class="ml-1"></span>
            </h3>
            <button @click="selectedDate = null; selectedEvents = []" class="text-white/70 hover:text-white transition-colors">
                <i class="bi bi-x-circle-fill text-2xl"></i>
            </button>
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hora</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Torneo & Cancha</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Categoría</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Encuentro</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <template x-for="item in selectedEvents" :key="item.id">
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-club-primary bg-blue-50 inline-flex px-3 py-1 rounded-full"><i class="bi bi-clock mr-1"></i> <span x-text="item.hora"></span></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900" x-text="item.torneo"></div>
                                        <div class="text-xs text-gray-500 flex items-center mt-1">
                                            <i class="bi bi-geo-alt-fill text-indigo-400 mr-1"></i> <span x-text="item.cancha"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800" x-text="item.categoriaUno + (item.categoriaDos ? ' / ' + item.categoriaDos : '')"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2 text-sm font-semibold">
                                            <span class="text-gray-900" x-text="item.eLocal"></span>
                                            <span class="text-gray-400 text-xs px-2 py-0.5 bg-gray-100 rounded">VS</span>
                                            <span class="text-red-600 font-bold" x-text="item.eVisitante"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button @click="openShow(item)" title="Ver Detalles" class="p-2 text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors focus:outline-none">
                                                <i class="bi bi-eye text-lg"></i>
                                            </button>
                                            
                                            @hasanyrole('Admin|Profesor')
                                                <button @click="openEdit(item)" title="Editar" class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors focus:outline-none">
                                                    <i class="bi bi-pencil-square text-lg"></i>
                                                </button>
                                            @endhasanyrole

                                            @hasrole('Admin')
                                                <button @click="openDelete(item)" title="Eliminar" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors focus:outline-none">
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
            </template>
        </div>
      </div>
    </div>

    <!-- CREATE MODAL -->
    <div x-show="openCreateModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="openCreateModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
          
          <form action="{{ route('programming.store') }}" method="post" class="requires-validation" novalidate>
            @csrf
            <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Agregar Nueva Programación</h3>
                <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                  <i class="bi bi-x-lg text-xl"></i>
                </button>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Torneo <span class="text-red-500">*</span></label>
                  <input type="text" name="torneo" placeholder="Ej: Liga Betplay Futsal" value="{{ old('torneo') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Cancha <span class="text-red-500">*</span></label>
                  <input type="text" name="cancha" placeholder="Lugar del partido" value="{{ old('cancha') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría 1 <span class="text-red-500">*</span></label>
                  <input type="text" name="categoriaUno" placeholder="Ej: 2005" value="{{ old('categoriaUno') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría 2</label>
                  <input type="text" name="categoriaDos" placeholder="Opcional" value="{{ old('categoriaDos') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                  <input type="date" name="fecha" min="{{now()->format('Y-m-d')}}" value="{{ old('fecha') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Hora <span class="text-red-500">*</span></label>
                  <input type="time" name="hora" value="{{ old('hora') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo Local <span class="text-red-500">*</span></label>
                  <input type="text" name="eLocal" value="Jackeline FS" readonly required class="block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-gray-500 cursor-not-allowed">
                </div>
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo Visitante <span class="text-red-500">*</span></label>
                  <input type="text" name="eVisitante" placeholder="Rival" value="{{ old('eVisitante') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all">
                </div>

                <!-- Lista de jugadores convocados (Transfer List) -->
                <div class="lg:col-span-3" x-data="{
                    searchLeft: '',
                    searchRight: '',
                    available: [
                        @foreach($studentList ?? [] as $student)
                            { id: {{ $student->id }}, name: '{{ addslashes($student->nomDeportista) }}', category: '{{ $student->Categoria }}' },
                        @endforeach
                    ],
                    selected: [],
                    
                    get filteredAvailable() {
                        let filtered = this.available;
                        if (this.searchLeft !== '') {
                            filtered = this.available.filter(p => p.name.toLowerCase().includes(this.searchLeft.toLowerCase()) || p.category.toString().includes(this.searchLeft));
                        }
                        return filtered.slice(0, 5);
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
                    }
                }">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Seleccionar Jugadores Convocados <span class="text-red-500">*</span></label>
                  
                  <template x-for="player in selected" :key="player.id">
                      <input type="hidden" name="jugadores_convocados[]" :value="player.name">
                  </template>

                  <div class="flex flex-col md:flex-row gap-4 items-stretch">
                      <!-- Available -->
                      <div class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                          <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                              <span class="font-bold text-sm text-gray-700">
                                  Disponibles <span class="font-normal text-xs text-gray-500">(Mostrando <span x-text="filteredAvailable.length"></span> de <span x-text="available.length"></span>)</span>
                              </span>
                              <button type="button" @click="moveAllToSelected" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Convocar Visibles <i class="bi bi-chevron-double-right"></i></button>
                          </div>
                          <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                              <div class="relative">
                                  <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                  <input type="text" x-model="searchLeft" placeholder="Buscar nombre o año..." class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                              </div>
                          </div>
                          <div class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-gray-50/30">
                              <template x-for="player in filteredAvailable" :key="player.id">
                                  <div @click="moveToSelected(player)" class="flex justify-between items-center p-2 rounded-lg hover:bg-indigo-50 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors group">
                                      <div><div class="text-sm font-medium text-gray-800 group-hover:text-indigo-700" x-text="player.name"></div></div>
                                      <div class="flex items-center space-x-2">
                                          <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-200 text-gray-600 group-hover:bg-indigo-200 group-hover:text-indigo-800" x-text="player.category"></span>
                                          <i class="bi bi-arrow-right-short text-gray-300 group-hover:text-indigo-500 text-lg"></i>
                                      </div>
                                  </div>
                              </template>
                              <div x-show="filteredAvailable.length === 0" class="text-center py-4 text-sm text-gray-400 italic">No hay jugadores</div>
                          </div>
                      </div>

                      <!-- Selected -->
                      <div class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                          <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                              <span class="font-bold text-sm text-indigo-900">Convocados (<span x-text="selected.length"></span>)</span>
                              <button type="button" @click="moveAllToAvailable" class="text-xs text-red-600 hover:text-red-800 font-semibold"><i class="bi bi-chevron-double-left"></i> Quitar Todos</button>
                          </div>
                          <div class="p-2 border-b border-indigo-50 bg-indigo-50/30">
                              <div class="relative">
                                  <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                  <input type="text" x-model="searchRight" placeholder="Buscar convocado..." class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                              </div>
                          </div>
                          <div class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-white">
                              <template x-for="player in filteredSelected" :key="player.id">
                                  <div @click="moveToAvailable(player)" class="flex justify-between items-center p-2 rounded-lg bg-green-50 hover:bg-red-50 cursor-pointer border border-green-100 hover:border-red-100 transition-colors group">
                                      <div class="flex items-center space-x-2">
                                          <i class="bi bi-arrow-left-short text-transparent group-hover:text-red-500 text-lg transition-colors"></i>
                                          <div class="text-sm font-bold text-green-800 group-hover:text-red-700" x-text="player.name"></div>
                                      </div>
                                      <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-200 text-green-800 group-hover:bg-red-200 group-hover:text-red-800" x-text="player.category"></span>
                                  </div>
                              </template>
                              <div x-show="selected.length === 0" class="flex flex-col items-center justify-center h-full text-center p-4">
                                  <p class="text-sm text-gray-400">Haz clic en un jugador para convocarlo.</p>
                              </div>
                          </div>
                      </div>
                  </div>
                  <input type="checkbox" :checked="selected.length > 0" required class="opacity-0 absolute -z-10" oninvalid="this.setCustomValidity('Debes convocar al menos un jugador')" oninput="this.setCustomValidity('')">
                </div>
              </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
              <button type="submit" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                <i class="bi bi-save mr-2"></i> Guardar Programación
              </button>
              <button type="button" @click="openCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Cancelar
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="openEditModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openEditModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
          
          <form :action="'{{ route('programming.update', 999999) }}'.replace('999999', editingItem.id)" method="post" class="requires-validation">
            @method('put')
            @csrf
            <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl leading-6 font-bold text-gray-900">Actualizar Programación</h3>
                <button type="button" @click="openEditModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                  <i class="bi bi-x-lg text-xl"></i>
                </button>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Torneo</label>
                  <input type="text" name="torneo" :value="editingItem.torneo" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría 1</label>
                  <input type="text" name="categoriaUno" :value="editingItem.categoriaUno" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Cancha</label>
                  <input type="text" name="cancha" :value="editingItem.cancha" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Equipo Visitante</label>
                  <input type="text" name="eVisitante" :value="editingItem.eVisitante" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                  <input type="date" name="fecha" :value="editingItem.fecha" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Hora</label>
                  <input type="time" name="hora" :value="editingItem.hora" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                
                <!-- Edit Transfer List -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Editar Jugadores Convocados</label>
                  
                  <template x-for="player in editSelected" :key="player.id">
                      <input type="hidden" name="jugadores_convocados[]" :value="player.name">
                  </template>

                  <div class="flex flex-col md:flex-row gap-4 items-stretch">
                      <!-- Available -->
                      <div class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                          <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                              <span class="font-bold text-sm text-gray-700">Disponibles <span class="font-normal text-xs text-gray-500">(<span x-text="editFilteredAvailable.length"></span> de <span x-text="editAvailable.length"></span>)</span></span>
                              <button type="button" @click="editMoveAllToSelected" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Convocar Visibles <i class="bi bi-chevron-double-right"></i></button>
                          </div>
                          <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                              <div class="relative">
                                  <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                  <input type="text" x-model="editSearchLeft" placeholder="Buscar..." class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500">
                              </div>
                          </div>
                          <div class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-gray-50/30">
                              <template x-for="player in editFilteredAvailable" :key="player.id">
                                  <div @click="editMoveToSelected(player)" class="flex justify-between items-center p-2 rounded-lg hover:bg-indigo-50 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors group">
                                      <div><div class="text-sm font-medium text-gray-800 group-hover:text-indigo-700" x-text="player.name"></div></div>
                                      <div class="flex items-center space-x-2">
                                          <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-200 text-gray-600" x-text="player.category"></span>
                                          <i class="bi bi-arrow-right-short text-gray-300 group-hover:text-indigo-500 text-lg"></i>
                                      </div>
                                  </div>
                              </template>
                              <div x-show="editFilteredAvailable.length === 0" class="text-center py-4 text-sm text-gray-400 italic">No hay jugadores</div>
                          </div>
                      </div>

                      <!-- Selected -->
                      <div class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                          <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                              <span class="font-bold text-sm text-indigo-900">Convocados (<span x-text="editSelected.length"></span>)</span>
                              <button type="button" @click="editMoveAllToAvailable" class="text-xs text-red-600 hover:text-red-800 font-semibold"><i class="bi bi-chevron-double-left"></i> Quitar Todos</button>
                          </div>
                          <div class="p-2 border-b border-indigo-50 bg-indigo-50/30">
                              <div class="relative">
                                  <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                  <input type="text" x-model="editSearchRight" placeholder="Buscar..." class="w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500">
                              </div>
                          </div>
                          <div class="flex-1 overflow-y-auto h-auto min-h-[12rem] p-2 space-y-1 bg-white">
                              <template x-for="player in editFilteredSelected" :key="player.id">
                                  <div @click="editMoveToAvailable(player)" class="flex justify-between items-center p-2 rounded-lg bg-green-50 hover:bg-red-50 cursor-pointer border border-green-100 hover:border-red-100 transition-colors group">
                                      <div class="flex items-center space-x-2">
                                          <i class="bi bi-arrow-left-short text-transparent group-hover:text-red-500 text-lg"></i>
                                          <div class="text-sm font-bold text-green-800 group-hover:text-red-700" x-text="player.name"></div>
                                      </div>
                                      <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-200 text-green-800" x-text="player.category"></span>
                                  </div>
                              </template>
                              <div x-show="editSelected.length === 0" class="flex flex-col items-center justify-center h-full text-center p-4">
                                  <p class="text-sm text-gray-400">Haz clic en un jugador para convocarlo.</p>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
              <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-amber-500 text-base font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Guardar Cambios
              </button>
              <button type="button" @click="openEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- DELETE MODAL -->
    <div x-show="openDeleteModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openDeleteModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-bold text-gray-900">Eliminar Programación</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    ¿Estás seguro que deseas eliminar la programación para el torneo <strong x-text="deletingItem.torneo ? deletingItem.torneo.toUpperCase() : ''"></strong>? Esta acción no se puede deshacer.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
            <form :action="'{{ route('programming.destroy', 999999) }}'.replace('999999', deletingItem.id)" method="post" class="m-0">
              @csrf
              @method('delete')
              <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Sí, Eliminar
              </button>
            </form>
            <button type="button" @click="openDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- SHOW MODAL -->
    <div x-show="openShowModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openShowModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openShowModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="openShowModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
          
          <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
              <h3 class="text-xl font-bold text-white flex items-center">
                  <i class="bi bi-info-circle mr-2"></i> Detalles del Encuentro
              </h3>
              <button @click="openShowModal = false" class="text-indigo-200 hover:text-white transition-colors">
                  <i class="bi bi-x-circle-fill text-2xl"></i>
              </button>
          </div>

          <div class="bg-white px-6 py-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Detalles -->
                  <div class="space-y-4">
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
                              <p class="font-semibold text-gray-800"><i class="bi bi-calendar3 mr-1 text-indigo-400"></i> <span x-text="showingItem.fecha"></span></p>
                          </div>
                          <div>
                              <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Hora</p>
                              <p class="font-semibold text-gray-800"><i class="bi bi-clock mr-1 text-indigo-400"></i> <span x-text="showingItem.hora"></span></p>
                          </div>
                      </div>
                      <div class="flex justify-between border-t border-gray-100 pt-3">
                          <div>
                              <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Categoría</p>
                              <span class="inline-block mt-1 px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800" x-text="showingItem.categoriaUno + (showingItem.categoriaDos ? ' / ' + showingItem.categoriaDos : '')"></span>
                          </div>
                          <div>
                              <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Cancha</p>
                              <p class="font-semibold text-gray-800 mt-1"><i class="bi bi-geo-alt-fill mr-1 text-indigo-400"></i> <span x-text="showingItem.cancha"></span></p>
                          </div>
                      </div>
                  </div>

                  <!-- Convocados -->
                  <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                      <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-3">Jugadores Convocados (<span x-text="showingPlayers.length"></span>)</p>
                      <ul class="space-y-2 max-h-64 overflow-y-auto pr-2">
                          <template x-for="(player, index) in showingPlayers" :key="index">
                              <li class="flex items-center text-sm font-semibold text-gray-800 bg-white px-3 py-2 rounded shadow-sm border border-gray-100">
                                  <i class="bi bi-person-check-fill text-green-500 mr-2"></i>
                                  <span x-text="player"></span>
                              </li>
                          </template>
                          <template x-if="showingPlayers.length === 0">
                              <li class="text-sm text-gray-500 italic text-center py-4">No hay jugadores convocados</li>
                          </template>
                      </ul>
                  </div>
              </div>
          </div>
          <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end rounded-b-2xl border-t border-gray-100">
            <button type="button" @click="openShowModal = false" class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    function calendarApp() {
        return {
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            events: {!! $eventsByDate !!},
            selectedDate: null,
            selectedEvents: [],
            
            allStudents: [
                @foreach($studentList ?? [] as $student)
                    { id: {{ $student->id }}, name: '{{ addslashes($student->nomDeportista) }}', category: '{{ $student->Categoria }}' },
                @endforeach
            ],
            
            openCreateModal: false,
            openEditModal: false,
            openDeleteModal: false,
            openShowModal: false,
            editingItem: {},
            deletingItem: {},
            showingItem: {},
            showingPlayers: [],
            
            editSearchLeft: '',
            editSearchRight: '',
            editAvailable: [],
            editSelected: [],

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
                if(remaining === 7) return [];
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
                if(!dateStr) return '';
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
                } catch(e) {
                    parsed = raw.split(',');
                }
                // Limpiar comillas, corchetes y espacios en blanco residuales
                return parsed.map(p => p.toString().replace(/[\[\]"]/g, '').trim()).filter(p => p !== '');
            },
            
            // Show Modal Logic
            openShow(item) {
                this.showingItem = item;
                this.showingPlayers = this.parseConvocados(item.jugadores_convocados);
                this.openShowModal = true;
            },
            
            // Edit Modal Logic
            openEdit(item) {
                this.editingItem = item;
                
                let convocadosArray = this.parseConvocados(item.jugadores_convocados);
                this.editSelected = this.allStudents.filter(s => convocadosArray.includes(s.name));
                this.editAvailable = this.allStudents.filter(s => !convocadosArray.includes(s.name));
                
                this.editSearchLeft = '';
                this.editSearchRight = '';
                this.openEditModal = true;
            },
            get editFilteredAvailable() {
                let filtered = this.editAvailable;
                if (this.editSearchLeft !== '') {
                    filtered = this.editAvailable.filter(p => p.name.toLowerCase().includes(this.editSearchLeft.toLowerCase()) || p.category.toString().includes(this.editSearchLeft));
                }
                return filtered.slice(0, 5);
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
            
            openDelete(item) {
                this.deletingItem = item;
                this.openDeleteModal = true;
            }
        }
    }
  </script>
</x-app-layout>

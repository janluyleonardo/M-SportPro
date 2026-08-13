<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                    <i class="bi bi-calendar3 text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                        {{ __('Programación de Clases') }}
                    </h2>
                    <p class="text-[10px] font-black text-club-primary uppercase tracking-widest mt-1">
                        {{ $startOfWeek->isoFormat('D [de] MMMM') }} - {{ $endOfWeek->isoFormat('D [de] MMMM, YYYY') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Filtro de Semana -->
                <div class="flex items-center space-x-2 bg-white rounded-2xl p-1 shadow-sm border border-gray-100">
                    <a href="{{ route('schedules.index', ['date' => $startOfWeek->copy()->subWeek()->toDateString()]) }}" 
                       class="p-2 hover:bg-gray-50 rounded-xl transition-colors text-gray-400" title="Semana Anterior">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <form action="{{ route('schedules.index') }}" method="GET" class="flex items-center">
                        <input type="date" name="date" value="{{ $selectedDate }}" 
                               onchange="this.form.submit()"
                               class="border-none bg-transparent font-bold text-[10px] text-gray-600 focus:ring-0 cursor-pointer uppercase tracking-widest">
                    </form>
                    <a href="{{ route('schedules.index', ['date' => $startOfWeek->copy()->addWeek()->toDateString()]) }}" 
                       class="p-2 hover:bg-gray-50 rounded-xl transition-colors text-gray-400" title="Siguiente Semana">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                
                @role('Admin|SubAdmin')
                    <button
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-schedule' }))"
                        class="inline-flex items-center px-6 py-3 bg-club-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-indigo-100">
                        <i class="bi bi-plus-lg mr-2"></i> Programar
                    </button>
                @endrole
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        editDay: '', 
        editDate: '',
        editCategory: '', 
        editStart: '', 
        editEnd: '', 
        editTeacher: '', 
        editLocation: '',
        editObservations: '',
        editClubId: '',
        editUrl: ''
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @php
                $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @foreach($days as $day)
                    <div class="space-y-4">
                        <div class="bg-club-primary text-white p-4 rounded-2xl text-center text-[10px] font-black uppercase tracking-[0.2em] shadow-sm border-b-4 border-club-secondary">
                            {{ $day }}
                        </div>
                        
                        <div class="space-y-3">
                            @php $daySchedules = $schedules->where('day_of_week', $day); @endphp
                            
                            @forelse($daySchedules as $schedule)
                                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all relative group border-l-4 border-l-club-secondary">
                                    <div class="text-[10px] font-black text-club-primary uppercase mb-1">{{ $schedule->category }}</div>
                                    <div class="text-sm font-bold text-gray-900">{{ date('g:i A', strtotime($schedule->start_time)) }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold mt-1">
                                        <i class="bi bi-person-fill mr-1 text-club-secondary"></i> {{ $schedule->teacher->name }}
                                    </div>
                                    @if($schedule->location)
                                        <div class="text-[9px] font-black text-white bg-club-primary/80 px-2 py-0.5 rounded-full inline-block mt-1.5">
                                            <i class="bi bi-geo-alt-fill mr-0.5"></i> {{ $schedule->location }}
                                        </div>
                                    @endif

                                    @role('Admin|SubAdmin')
                                        @if($schedule->attendances_exists)
                                            <div class="mt-2 flex items-center text-[8px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg border border-green-100 uppercase tracking-widest">
                                                <i class="bi bi-check-all text-sm mr-1"></i> Asistencia Tomada
                                            </div>
                                        @endif
                                    @endrole

                                    @if($schedule->observations)
                                        <div class="mt-2 text-[9px] text-gray-500 italic bg-gray-50 p-2 rounded-lg border border-gray-100">
                                            <i class="bi bi-info-circle mr-1"></i> {{ $schedule->observations }}
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    @role('Admin|SubAdmin')
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-2">
                                            <button @click="
                                                editUrl = '{{ route('schedules.update', $schedule) }}';
                                                editDay = '{{ $schedule->day_of_week }}';
                                                editDate = '{{ $schedule->date }}';
                                                editCategory = '{{ $schedule->category }}';
                                                editStart = '{{ $schedule->start_time }}';
                                                editEnd = '{{ $schedule->end_time }}';
                                                editTeacher = '{{ $schedule->user_id }}';
                                                editLocation = '{{ $schedule->location }}';
                                                editObservations = '{{ $schedule->observations }}';
                                                editClubId = '{{ $schedule->club_id }}';
                                                $dispatch('open-modal', 'edit-schedule');
                                            " class="text-blue-400 hover:text-blue-600 transition-colors">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

                                            @role('Admin')
                                                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
                                                      onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar horario', '¿Deseas eliminar esta clase programada?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    @endrole
                                </div>
                            @empty
                                <div class="py-4 text-center text-[10px] text-gray-300 italic border-2 border-dashed border-gray-50 rounded-2xl">
                                    Sin clases
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modal Programar Clase (Add) -->
        <x-modal name="add-schedule" focusable>
            <div class="p-8">
                <h2 class="text-2xl font-black text-gray-900 mb-8 flex items-center">
                    <i class="bi bi-calendar-plus text-club-primary mr-3"></i> Nueva Programación
                </h2>

                <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6"
                      x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    @if(auth()->user()->is_super_admin && $clubs->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Club</label>
                        <select name="club_id" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                            <option value="">Seleccione el Club...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Fecha de la Clase</label>
                            <input type="date" name="date" value="{{ $selectedDate }}" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                        </div>
                        <div
                            x-data="{
                                open: false,
                                search: '',
                                selected: '',
                                categories: {{ $categories->toJson() }},
                                get filtered() {
                                    if (!this.search) return this.categories;
                                    return this.categories.filter(c => c.toLowerCase().includes(this.search.toLowerCase()));
                                },
                                choose(val) {
                                    this.selected = val;
                                    this.search = val;
                                    this.open = false;
                                }
                            }"
                            class="relative"
                            @click.outside="open = false"
                        >
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Categoría</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="search"
                                    @focus="open = true"
                                    @input="open = true; selected = ''"
                                    placeholder="Buscar o elegir categoría..."
                                    autocomplete="off"
                                    class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 pr-10 font-bold text-gray-700 focus:ring-club-primary focus:border-club-primary"
                                    required
                                >
                                <button type="button" @click="open = !open" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-club-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="category" :value="search">
                            <!-- Dropdown list -->
                            <div
                                x-show="open && filtered.length > 0"
                                x-transition
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-lg max-h-48 overflow-y-auto"
                            >
                                <template x-for="cat in filtered" :key="cat">
                                    <button
                                        type="button"
                                        @click="choose(cat)"
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-club-primary/10 hover:text-club-primary transition-colors first:rounded-t-2xl last:rounded-b-2xl"
                                        :class="{'bg-club-primary/10 text-club-primary': selected === cat}"
                                        x-text="cat"
                                    ></button>
                                </template>
                            </div>
                            <!-- No results -->
                            <div
                                x-show="open && filtered.length === 0 && search.length > 0"
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-lg px-4 py-3 text-xs text-gray-400 italic"
                            >
                                No hay categorías que coincidan. Se usará "<span class="font-bold text-gray-600" x-text="search"></span>".
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Hora Inicio</label>
                            <input type="time" name="start_time" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Hora Fin</label>
                            <input type="time" name="end_time" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Profesor Asignado</label>
                            <select name="user_id" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">
                                <i class="bi bi-geo-alt-fill mr-1 text-club-primary"></i> Cancha / Ubicación
                            </label>
                            <select name="location" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700">
                                <option value="">-- Sin asignar --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Observaciones</label>
                        <textarea name="observations" rows="2" placeholder="Motivo de la programación o notas adicionales..." class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700 focus:ring-club-primary focus:border-club-primary"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-10">
                        <button type="button"
                                :disabled="loading"
                                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'add-schedule' }))"
                                class="px-8 py-3 bg-gray-100 text-gray-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            Cancelar
                        </button>
                        <button type="submit"
                                :disabled="loading"
                                class="inline-flex items-center gap-2 px-10 py-4 bg-club-primary text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-indigo-100 disabled:opacity-70 disabled:cursor-not-allowed">
                            <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <i x-show="!loading" class="bi bi-floppy-fill"></i>
                            <span x-text="loading ? 'Guardando...' : 'Guardar Horario'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- Modal Editar Clase (Edit) -->
        <x-modal name="edit-schedule" focusable>
            <div class="p-8">
                <h2 class="text-2xl font-black text-gray-900 mb-8 flex items-center">
                    <i class="bi bi-pencil-square text-blue-500 mr-3"></i> Editar Programación
                </h2>

                <form :action="editUrl" method="POST" class="space-y-6"
                      x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    @method('PUT')
                    @if(auth()->user()->is_super_admin && $clubs->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Club</label>
                        <select name="club_id" x-model="editClubId" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                            <option value="">Seleccione el Club...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Fecha de la Clase</label>
                            <input type="date" name="date" x-model="editDate" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                        </div>
                        <div
                            x-data="{
                                open: false,
                                search: editCategory,
                                selected: editCategory,
                                categories: {{ $categories->toJson() }},
                                get filtered() {
                                    if (!this.search) return this.categories;
                                    return this.categories.filter(c => c.toLowerCase().includes(this.search.toLowerCase()));
                                },
                                choose(val) {
                                    this.selected = val;
                                    this.search = val;
                                    editCategory = val;
                                    this.open = false;
                                }
                            }"
                            x-init="$watch('editCategory', val => { search = val; selected = val; })"
                            class="relative"
                            @click.outside="open = false"
                        >
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Categoría</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="search"
                                    @focus="open = true"
                                    @input="open = true; selected = ''"
                                    placeholder="Buscar o elegir categoría..."
                                    autocomplete="off"
                                    class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 pr-10 font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                <button type="button" @click="open = !open" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="category" :value="search">
                            <!-- Dropdown list -->
                            <div
                                x-show="open && filtered.length > 0"
                                x-transition
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-lg max-h-48 overflow-y-auto"
                            >
                                <template x-for="cat in filtered" :key="cat">
                                    <button
                                        type="button"
                                        @click="choose(cat)"
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-500 transition-colors first:rounded-t-2xl last:rounded-b-2xl"
                                        :class="{'bg-blue-50 text-blue-500': selected === cat}"
                                        x-text="cat"
                                    ></button>
                                </template>
                            </div>
                            <!-- No results -->
                            <div
                                x-show="open && filtered.length === 0 && search.length > 0"
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-lg px-4 py-3 text-xs text-gray-400 italic"
                            >
                                No hay categorías que coincidan. Se usará "<span class="font-bold text-gray-600" x-text="search"></span>".
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Hora Inicio</label>
                            <input type="time" name="start_time" x-model="editStart" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Hora Fin</label>
                            <input type="time" name="end_time" x-model="editEnd" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Profesor Asignado</label>
                            <select name="user_id" x-model="editTeacher" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">
                                <i class="bi bi-geo-alt-fill mr-1 text-club-primary"></i> Cancha / Ubicación
                            </label>
                            <select name="location" x-model="editLocation" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700">
                                <option value="">-- Sin asignar --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Observaciones / Motivo del cambio</label>
                        <textarea name="observations" x-model="editObservations" rows="2" placeholder="¿Por qué se reasigna o edita esta clase?..." class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-10">
                        <button type="button"
                                :disabled="loading"
                                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-schedule' }))"
                                class="px-8 py-3 bg-gray-100 text-gray-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            Cancelar
                        </button>
                        <button type="submit"
                                :disabled="loading"
                                class="inline-flex items-center gap-2 px-10 py-4 bg-blue-500 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-blue-100 disabled:opacity-70 disabled:cursor-not-allowed">
                            <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <i x-show="!loading" class="bi bi-floppy-fill"></i>
                            <span x-text="loading ? 'Actualizando...' : 'Actualizar Horario'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>

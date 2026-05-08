<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                    <i class="bi bi-calendar3 text-xl"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Programación de Clases') }}
                </h2>
            </div>
            
            @role('Admin')
                <button
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-schedule' }))"
                    class="inline-flex items-center px-6 py-3 bg-club-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-indigo-100">
                    <i class="bi bi-plus-lg mr-2"></i> Programar Clase
                </button>
            @endrole
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        editDay: '', 
        editCategory: '', 
        editStart: '', 
        editEnd: '', 
        editTeacher: '', 
        editLocation: '',
        editObservations: '',
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

                                    @if($schedule->observations)
                                        <div class="mt-2 text-[9px] text-gray-500 italic bg-gray-50 p-2 rounded-lg border border-gray-100">
                                            <i class="bi bi-info-circle mr-1"></i> {{ $schedule->observations }}
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    @role('Admin')
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-2">
                                            <button @click="
                                                editUrl = '{{ route('schedules.update', $schedule) }}';
                                                editDay = '{{ $schedule->day_of_week }}';
                                                editCategory = '{{ $schedule->category }}';
                                                editStart = '{{ $schedule->start_time }}';
                                                editEnd = '{{ $schedule->end_time }}';
                                                editTeacher = '{{ $schedule->user_id }}';
                                                editLocation = '{{ $schedule->location }}';
                                                editObservations = '{{ $schedule->observations }}';
                                                $dispatch('open-modal', 'edit-schedule');
                                            " class="text-blue-400 hover:text-blue-600 transition-colors">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

                                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
                                                  onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar horario', '¿Deseas eliminar esta clase programada?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </form>
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

                <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Día de la Semana</label>
                            <select name="day_of_week" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                                @foreach($days as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Categoría</label>
                            <input type="text" name="category" placeholder="Ej: 2010" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold" required>
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
                                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'add-schedule' }))"
                                class="px-8 py-3 bg-gray-100 text-gray-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="px-10 py-4 bg-club-primary text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-indigo-100">
                            Guardar Horario
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

                <form :action="editUrl" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Día de la Semana</label>
                            <select name="day_of_week" x-model="editDay" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700" required>
                                @foreach($days as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Categoría</label>
                            <input type="text" name="category" x-model="editCategory" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold" required>
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
                                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-schedule' }))"
                                class="px-8 py-3 bg-gray-100 text-gray-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="px-10 py-4 bg-blue-500 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-blue-100">
                            Actualizar Horario
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>

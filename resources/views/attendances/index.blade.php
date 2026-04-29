<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-green-100 rounded-lg text-green-600">
                <i class="bi bi-check2-square text-xl"></i>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Control de Asistencia') }}
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Clases de Hoy: {{ now()->locale('es')->dayName }} {{ now()->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-6">
                @forelse($schedules as $schedule)
                    <div class="bg-white overflow-hidden shadow-sm rounded-[2rem] border border-gray-100 hover:shadow-xl transition-all duration-500 group">
                        <div class="p-8 flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <!-- Time Badge -->
                                <div class="bg-club-primary text-white p-4 rounded-3xl text-center min-w-[100px] border-b-4 border-club-secondary shadow-lg shadow-blue-100">
                                    <div class="text-[10px] font-black uppercase opacity-60 mb-1">Inicia</div>
                                    <div class="text-xl font-black">{{ date('g:i', strtotime($schedule->start_time)) }}</div>
                                    <div class="text-[10px] font-black uppercase opacity-60 mt-1">{{ date('A', strtotime($schedule->start_time)) }}</div>
                                </div>

                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Categoría {{ $schedule->category }}</h3>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="flex items-center text-xs font-bold text-gray-400">
                                            <i class="bi bi-person-badge mr-2 text-indigo-500"></i> {{ $schedule->teacher->name }}
                                        </span>
                                        @if($schedule->location)
                                        <span class="flex items-center text-xs font-bold text-gray-400">
                                            <i class="bi bi-geo-alt mr-2 text-red-500"></i> {{ $schedule->location }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('attendances.show', $schedule) }}" class="inline-flex items-center px-8 py-4 bg-gray-50 text-gray-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-club-primary hover:text-white transition-all shadow-sm border border-gray-100">
                                Tomar Lista <i class="bi bi-arrow-right ml-3"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-20 rounded-[3rem] border-2 border-dashed border-gray-100 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="bi bi-calendar-x text-3xl text-gray-200"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest">No hay clases programadas</h3>
                        <p class="text-gray-300 mt-2 font-bold">No tienes clases asignadas para el día de hoy.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

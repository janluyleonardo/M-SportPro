<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('attendances.index') }}" class="p-2 bg-gray-100 rounded-lg text-gray-500 hover:bg-gray-200">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-black text-2xl text-gray-900 leading-tight uppercase tracking-tight">
                        Asistencia: {{ $schedule->category }}
                    </h2>
                    <p class="text-[10px] font-black text-club-primary uppercase tracking-[0.2em]">
                        {{ $schedule->day_of_week }} | {{ date('g:i A', strtotime($schedule->start_time)) }}
                    </p>
                </div>
            </div>

            <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
                <span class="text-[10px] font-black text-club-primary/50 uppercase block">Total Alumnos</span>
                <span class="text-xl font-black text-club-primary">{{ $students->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_schedule_id" value="{{ $schedule->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($students as $student)
                        @php
                            // Verificar estado de pago para el mes actual
                            $hasPaid = \App\Models\Payment::where('student_id', $student->id)
                                ->where('month', now()->month)
                                ->where('year', now()->year)
                                ->first();
                        @endphp
                        
                        @if($hasPaid)
                            {{-- ✅ Estudiante AL DÍA — puede ser marcado presente o ausente --}}
                            <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:border-club-primary/30 transition-all">
                                <div class="flex items-center space-x-4">
                                    <div class="h-14 w-14 bg-gray-100 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100 relative">
                                        @if($student->Photo)
                                            <img src="{{ asset($student->Photo) }}" class="h-full w-full object-cover" onerror="this.style.display='none'">
                                        @endif
                                        <span class="text-lg font-black text-gray-300">{{ substr($student->nomDeportista, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 leading-tight">{{ $student->nomDeportista }}</h4>
                                        <span class="text-[9px] font-black bg-green-50 text-green-600 px-2 py-0.5 rounded-full border border-green-100 uppercase mt-1 inline-block">
                                            <i class="bi bi-check-circle-fill mr-1"></i> Al Día ({{ $hasPaid->classes_used }}/8)
                                        </span>
                                        @if($student->balance > 0)
                                            <span class="text-[9px] font-black bg-orange-50 text-orange-600 px-2 py-0.5 rounded-full border border-orange-100 uppercase mt-1 inline-block ml-1">
                                                Deuda: ${{ number_format($student->balance, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex bg-gray-50 p-1.5 rounded-2xl">
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="students[{{ $student->id }}]" 
                                               value="present" 
                                               class="hidden peer" 
                                               {{ ($existingAttendances[$student->id] ?? 'present') === 'present' ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 transition-all">
                                            Presente
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="students[{{ $student->id }}]" value="absent" class="hidden peer" {{ ($existingAttendances[$student->id] ?? '') === 'absent' ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm text-gray-400 transition-all">
                                            Faltó
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @else
                            {{-- 🔒 Estudiante SIN PAGO — BLOQUEADO, forzado a absent --}}
                            <div class="bg-red-50/50 p-4 rounded-[2rem] shadow-sm border-2 border-red-200 border-dashed flex items-center justify-between opacity-70 relative overflow-hidden">
                                {{-- Forzar absent --}}
                                <input type="hidden" name="students[{{ $student->id }}]" value="absent">

                                <div class="flex items-center space-x-4">
                                    <div class="h-14 w-14 bg-red-100 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-red-200 relative">
                                        <i class="bi bi-lock-fill text-red-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-600 leading-tight line-through decoration-red-300">{{ $student->nomDeportista }}</h4>
                                        <div class="mt-1">
                                            <span class="text-[9px] font-black bg-red-600 text-white px-3 py-1 rounded-full uppercase shadow-sm inline-flex items-center">
                                                <i class="bi bi-x-circle-fill mr-1"></i> BLOQUEADO — DEUDA: ${{ number_format($student->balance, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="text-[9px] text-red-400 font-semibold mt-1 italic">
                                            <i class="bi bi-info-circle mr-0.5"></i> Dirigir a oficina para legalizar pago
                                        </p>
                                    </div>
                                </div>

                                {{-- Indicador de bloqueo --}}
                                <div class="flex flex-col items-center space-y-1">
                                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center border border-red-200">
                                        <i class="bi bi-slash-circle text-red-400 text-lg"></i>
                                    </div>
                                    <span class="text-[8px] font-black text-red-400 uppercase tracking-wider">No Apto</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-12 sticky bottom-8 flex justify-center">
                    <button type="submit" class="px-12 py-5 bg-club-primary text-white rounded-[2rem] font-black text-sm uppercase tracking-widest hover:opacity-90 transition-all shadow-2xl shadow-blue-200 flex items-center border-b-4 border-club-secondary">
                        <i class="bi bi-cloud-arrow-up mr-3 text-lg"></i> Guardar Asistencia de Hoy
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('attendances.index') }}" class="p-2 bg-gray-100 rounded-lg text-gray-500 hover:bg-gray-200">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-black text-2xl text-gray-900 leading-tight uppercase tracking-tight">
                        Asistencia: {{ __($schedule->category) }}
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

            {{-- =====================================================================
                 FORMULARIO PRINCIPAL DE ASISTENCIA
                 ID "attendance-form" — los radios y hidden inputs pertenecen aquí
                 ===================================================================== --}}
            <form id="attendance-form" action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_schedule_id" value="{{ $schedule->id }}">

                @if($existingAttendances->count() > 0)
                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl mb-6 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-100 p-2 rounded-xl text-indigo-600">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <p class="text-xs font-bold text-indigo-700">Ya se tomó asistencia para esta clase hoy. Puedes realizar correcciones si es necesario.</p>
                        </div>
                        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">{{ now()->format('d/m/Y') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($students as $student)
                        @php
                            $hasPaid = \App\Models\Payment::where('student_id', $student->id)
                                ->where('month', now()->month)
                                ->where('year', now()->year)
                                ->first();
                            $override = $overrides[$student->id] ?? null;
                        @endphp

                        @if($hasPaid)
                            {{-- ✅ Estudiante AL DÍA --}}
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
                                        @php
                                            $slot = $student->attendanceSlots()
                                                ->where('month', now()->month)
                                                ->where('year', now()->year)
                                                ->first();
                                            $used = $slot ? $slot->classes_used : 0;
                                        @endphp
                                        <span class="text-[9px] font-black bg-green-50 text-green-600 px-2 py-0.5 rounded-full border border-green-100 uppercase mt-1 inline-block">
                                            <i class="bi bi-check-circle-fill mr-1"></i> Al Día ({{ $used }}/8)
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
                                               form="attendance-form"
                                               {{ ($existingAttendances[$student->id] ?? 'present') === 'present' ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 transition-all">
                                            Presente
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="students[{{ $student->id }}]" value="absent" class="hidden peer" form="attendance-form" {{ ($existingAttendances[$student->id] ?? '') === 'absent' ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm text-gray-400 transition-all">
                                            Faltó
                                        </div>
                                    </label>
                                </div>
                            </div>

                        @elseif($override)
                            {{-- 🔓 HABILITADO POR ADMIN --}}
                            <div class="bg-amber-50 p-4 rounded-[2rem] shadow-sm border-2 border-amber-300 flex items-center justify-between group transition-all relative">
                                <div class="flex items-center space-x-4">
                                    <div class="h-14 w-14 bg-amber-100 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-amber-200 relative">
                                        @if($student->Photo)
                                            <img src="{{ asset($student->Photo) }}" class="h-full w-full object-cover" onerror="this.style.display='none'">
                                        @endif
                                        <span class="text-lg font-black text-amber-400">{{ substr($student->nomDeportista, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 leading-tight">{{ $student->nomDeportista }}</h4>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            <span class="text-[9px] font-black bg-amber-500 text-white px-2 py-0.5 rounded-full uppercase inline-flex items-center shadow-sm">
                                                <i class="bi bi-unlock-fill mr-1"></i> Habilitado por Admin
                                            </span>
                                            <span class="text-[9px] font-black bg-red-100 text-red-500 px-2 py-0.5 rounded-full border border-red-200 uppercase inline-block">
                                                Deuda: ${{ number_format($student->balance, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="text-[9px] text-amber-600 font-semibold mt-1">
                                            <i class="bi bi-person-check-fill mr-0.5"></i>
                                            Autorizado por: {{ $override->authorizedBy->name }}
                                            @if($override->reason)
                                                · {{ $override->reason }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    {{-- Controles Presente / Faltó — asociados al form principal --}}
                                    <div class="flex bg-gray-50 p-1.5 rounded-2xl">
                                        <label class="cursor-pointer">
                                            <input type="radio"
                                                   name="students[{{ $student->id }}]"
                                                   value="present"
                                                   class="hidden peer"
                                                   form="attendance-form"
                                                   {{ ($existingAttendances[$student->id] ?? 'present') === 'present' ? 'checked' : '' }}>
                                            <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 transition-all">
                                                Presente
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="students[{{ $student->id }}]" value="absent" class="hidden peer" form="attendance-form" {{ ($existingAttendances[$student->id] ?? '') === 'absent' ? 'checked' : '' }}>
                                            <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm text-gray-400 transition-all">
                                                Faltó
                                            </div>
                                        </label>
                                    </div>

                                    {{-- Botón revocar — referencia form externo override-destroy-{id} --}}
                                    @role('Admin')
                                    <button type="submit"
                                            form="override-destroy-{{ $override->id }}"
                                            onclick="return confirmActionDirect('¿Deseas revocar la habilitación de {{ addslashes($student->nomDeportista) }}? Volverá a quedar bloqueado.')"
                                            class="text-[9px] font-black text-red-400 hover:text-red-600 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                        <i class="bi bi-lock-fill"></i> Revocar
                                    </button>
                                    @endrole
                                </div>
                            </div>

                        @else
                            {{-- 🔒 Estudiante BLOQUEADO sin override --}}
                            <div class="bg-red-50/50 p-4 rounded-[2rem] shadow-sm border-2 border-red-200 border-dashed flex items-center justify-between relative overflow-visible"
                                 x-data="{ showForm: false }">

                                {{-- Forzar absent en el form principal --}}
                                <input type="hidden" name="students[{{ $student->id }}]" value="absent" form="attendance-form">

                                <div class="flex items-center space-x-4">
                                    <div class="h-14 w-14 bg-red-100 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-red-200">
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

                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center border border-red-200">
                                        <i class="bi bi-slash-circle text-red-400 text-lg"></i>
                                    </div>
                                    <span class="text-[8px] font-black text-red-400 uppercase tracking-wider">No Apto</span>

                                    @role('Admin|SubAdmin')
                                    <button type="button"
                                            @click="showForm = !showForm"
                                            class="text-[9px] font-black text-amber-600 hover:text-amber-800 uppercase tracking-widest flex items-center gap-1 transition-colors mt-1 bg-amber-50 border border-amber-200 px-2 py-1 rounded-lg">
                                        <i class="bi bi-unlock-fill"></i> Habilitar
                                    </button>
                                    @endrole
                                </div>

                                {{-- Panel de habilitación — inputs referenciados al form externo override-store-{student_id} --}}
                                @role('Admin|SubAdmin')
                                <div x-show="showForm"
                                     x-transition
                                     class="absolute left-0 right-0 top-full mt-2 z-40 bg-white border border-amber-200 rounded-2xl shadow-xl p-4">
                                    <p class="text-xs font-black text-gray-700 mb-3 flex items-center gap-2">
                                        <i class="bi bi-unlock-fill text-amber-500"></i>
                                        Habilitar a <span class="text-amber-600">{{ $student->nomDeportista }}</span> para esta clase
                                    </p>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Motivo / Acuerdo (opcional)</label>
                                            {{-- textarea asociado al form externo mediante form= --}}
                                            <textarea name="reason"
                                                      form="override-store-{{ $student->id }}"
                                                      rows="2"
                                                      placeholder="Ej: Acuerdo de pago, pago parcial recibido..."
                                                      class="w-full border border-gray-200 bg-gray-50 rounded-xl p-2 text-xs font-semibold text-gray-700 focus:ring-amber-400 focus:border-amber-400"></textarea>
                                        </div>
                                        <div class="flex gap-2 justify-end">
                                            <button type="button" @click="showForm = false"
                                                    class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs uppercase hover:bg-gray-200 transition-all">
                                                Cancelar
                                            </button>
                                            {{-- Botón submit referencia al form externo --}}
                                            <button type="submit"
                                                    form="override-store-{{ $student->id }}"
                                                    class="px-4 py-2 bg-amber-500 text-white rounded-xl font-black text-xs uppercase hover:bg-amber-600 transition-all shadow-sm flex items-center gap-1">
                                                <i class="bi bi-unlock-fill"></i> Confirmar Habilitación
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endrole
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-12 sticky bottom-8 flex justify-center">
                    <button type="submit" form="attendance-form" class="px-12 py-5 bg-club-primary text-white rounded-[2rem] font-black text-sm uppercase tracking-widest hover:opacity-90 transition-all shadow-2xl shadow-blue-200 flex items-center border-b-4 border-club-secondary group">
                        <i class="bi {{ $existingAttendances->count() > 0 ? 'bi-pencil-square' : 'bi-cloud-arrow-up' }} mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                        {{ $existingAttendances->count() > 0 ? 'Actualizar Asistencia' : 'Guardar Asistencia de Hoy' }}
                    </button>
                </div>
            </form>

            {{-- FORMS DE OVERRIDE — FUERA del form principal --}}
            @foreach($students as $student)
                @php $override = $overrides[$student->id] ?? null; @endphp

                @if($override)
                    {{-- Form REVOCAR: solo Admin (es un DELETE) --}}
                    @role('Admin')
                    <form id="override-destroy-{{ $override->id }}"
                          action="{{ route('attendances.override.destroy', $override) }}"
                          method="POST"
                          style="display:none">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endrole
                @else
                    {{-- Form CREAR override: Admin y SubAdmin --}}
                    @role('Admin|SubAdmin')
                    <form id="override-store-{{ $student->id }}"
                          action="{{ route('attendances.override.store') }}"
                          method="POST"
                          style="display:none">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="class_schedule_id" value="{{ $schedule->id }}">
                    </form>
                    @endrole
                @endif
            @endforeach


        </div>
    </div>

    <script>
        function confirmActionDirect(message) {
            return confirm(message);
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('psychological-followups.index') }}" class="text-sm font-bold text-club-primary hover:underline">
                <i class="bi bi-arrow-left mr-1"></i> Seguimiento psicológico
            </a>
            <h2 class="mt-2 text-xl font-black text-gray-900">Nueva evaluación</h2>
            <p class="mt-1 text-sm text-gray-500">Abre cada bloque y selecciona una calificación de 0 a 5 para cada criterio.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <p class="font-bold">Revisa la información del formulario:</p>
                    <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('psychological-followups.store') }}" class="space-y-6">
                @csrf
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <h3 class="text-base font-black text-gray-900">Datos de la evaluación</h3>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="student_id" class="mb-1 block text-sm font-bold text-gray-700">Deportista <span class="text-red-500">*</span></label>
                            <select id="student_id" name="student_id" required class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                                <option value="">Selecciona un deportista activo</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id', request()->query('student_id')) == $student->id)>
                                        {{ $student->nomDeportista }} · {{ $student->Categoria }} · {{ $student->numDocumento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="evaluated_on" class="mb-1 block text-sm font-bold text-gray-700">Fecha <span class="text-red-500">*</span></label>
                            <input id="evaluated_on" name="evaluated_on" type="date" required max="{{ now()->toDateString() }}"
                                   value="{{ old('evaluated_on', now()->toDateString()) }}"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                        </div>
                        <div>
                            <label for="coach_name" class="mb-1 block text-sm font-bold text-gray-700">Entrenador</label>
                            <input id="coach_name" name="coach_name" value="{{ old('coach_name') }}" maxlength="255"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                        </div>
                        <div>
                            <label for="laterality" class="mb-1 block text-sm font-bold text-gray-700">Lateralidad</label>
                            <select id="laterality" name="laterality" class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                                <option value="">Sin especificar</option>
                                @foreach(['diestro' => 'Diestro', 'zurdo' => 'Zurdo', 'ambidiestro' => 'Ambidiestro'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('laterality') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="somatotype" class="mb-1 block text-sm font-bold text-gray-700">Somatotipo</label>
                            <select id="somatotype" name="somatotype" class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                                <option value="">Sin especificar</option>
                                @foreach(['ectomorfo' => 'Ectomorfo', 'mesomorfo' => 'Mesomorfo', 'endomorfo' => 'Endomorfo'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('somatotype') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="height_cm" class="mb-1 block text-sm font-bold text-gray-700">Talla (cm)</label>
                            <input id="height_cm" name="height_cm" type="number" step="0.01" min="0.01" max="300" value="{{ old('height_cm') }}"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                        </div>
                        <div>
                            <label for="weight_kg" class="mb-1 block text-sm font-bold text-gray-700">Peso (kg)</label>
                            <input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0.01" max="500" value="{{ old('weight_kg') }}"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                        </div>
                    </div>
                </section>

                @foreach($blocks as $blockCode => $block)
                    <details name="psychological-evaluation-blocks"
                             class="group scroll-mt-20 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm"
                             @if($loop->first || $errors->has('criteria')) open @endif>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 bg-gray-50 px-5 py-4 transition hover:bg-gray-100 [&::-webkit-details-marker]:hidden">
                            <span class="flex min-w-0 items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-club-primary">
                                    <i class="bi bi-clipboard2-check"></i>
                                </span>
                                <span class="font-black text-gray-900">{{ $block['label'] }}</span>
                            </span>
                            <span class="flex shrink-0 items-center gap-3">
                                <span class="text-xs font-semibold text-gray-400">{{ count($block['criteria']) }} criterios</span>
                                <i class="bi bi-chevron-down text-sm text-gray-400 transition-transform group-open:rotate-180"></i>
                            </span>
                        </summary>
                        <div class="divide-y divide-gray-100 border-t border-gray-100">
                            @foreach($block['criteria'] as $criterionCode => $criterionLabel)
                                <div class="flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-800">{{ $criterionLabel }}</p>
                                        <details class="mt-1.5" @if(old("criteria.{$criterionCode}.comment")) open @endif>
                                            <summary class="w-fit cursor-pointer list-none text-xs font-semibold text-gray-400 hover:text-club-primary [&::-webkit-details-marker]:hidden">
                                                <i class="bi bi-chat-left-text mr-1"></i>
                                                {{ old("criteria.{$criterionCode}.comment") ? 'Editar observación' : 'Agregar observación (opcional)' }}
                                            </summary>
                                            <input name="criteria[{{ $criterionCode }}][comment]"
                                                   value="{{ old("criteria.{$criterionCode}.comment") }}"
                                                   maxlength="2000"
                                                   placeholder="Escribe una observación"
                                                   aria-label="Observación para {{ $criterionLabel }}"
                                                   class="mt-2 w-full rounded-lg border-gray-200 text-xs focus:border-club-primary focus:ring-club-primary sm:max-w-md">
                                        </details>
                                    </div>
                                    <fieldset class="shrink-0">
                                        <legend class="sr-only">Calificación de {{ $criterionLabel }}, de 0 a 5</legend>
                                        <div class="flex items-center gap-1.5" role="radiogroup" aria-label="Calificación de {{ $criterionLabel }}">
                                            @foreach(range(0, 5) as $rating)
                                                <label class="cursor-pointer">
                                                    <input type="radio"
                                                           name="criteria[{{ $criterionCode }}][rating]"
                                                           value="{{ $rating }}"
                                                           required
                                                           class="peer sr-only psychological-rating"
                                                           aria-label="{{ $rating }}"
                                                           @checked(old("criteria.{$criterionCode}.rating") !== null && (string) old("criteria.{$criterionCode}.rating") === (string) $rating)>
                                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-sm font-bold text-gray-600 transition
                                                                 hover:border-blue-300 hover:bg-blue-50">
                                                        {{ $rating }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endforeach

                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <h3 class="text-base font-black text-gray-900">Conclusiones y propuesta</h3>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="conclusions" class="mb-1 block text-sm font-bold text-gray-700">Conclusiones</label>
                            <textarea id="conclusions" name="conclusions" rows="5" maxlength="10000"
                                      class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">{{ old('conclusions') }}</textarea>
                        </div>
                        <div>
                            <label for="proposal" class="mb-1 block text-sm font-bold text-gray-700">Propuesta de trabajo</label>
                            <textarea id="proposal" name="proposal" rows="5" maxlength="10000"
                                      class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">{{ old('proposal') }}</textarea>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('psychological-followups.index') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-center text-sm font-bold text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button class="rounded-xl bg-club-primary px-5 py-3 text-sm font-bold text-white shadow-sm hover:opacity-90">
                        <i class="bi bi-check2-circle mr-1"></i> Guardar evaluación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('details[name="psychological-evaluation-blocks"]').forEach((block) => {
            block.addEventListener('toggle', () => {
                if (block.open) {
                    block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</x-app-layout>

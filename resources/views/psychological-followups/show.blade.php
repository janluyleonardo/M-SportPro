<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('psychological-followups.index') }}" class="text-sm font-bold text-club-primary hover:underline">
                    <i class="bi bi-arrow-left mr-1"></i> Deportistas
                </a>
                <h2 class="mt-2 text-xl font-black text-gray-900">{{ $student->nomDeportista }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $student->Categoria }} · Documento {{ $student->numDocumento }}</p>
            </div>
            <a href="{{ route('psychological-followups.create', ['student_id' => $student->id]) }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-club-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:opacity-90">
                <i class="bi bi-plus-lg"></i> Nueva evaluación
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
            @endif

            @if(!$latestEvaluation)
                <section class="rounded-2xl border border-dashed border-blue-200 bg-blue-50 p-8 text-center">
                    <i class="bi bi-clipboard2-heart text-4xl text-club-primary"></i>
                    <h3 class="mt-3 font-black text-gray-900">Aún no hay evaluaciones</h3>
                    <p class="mt-1 text-sm text-gray-600">Registra la primera evaluación para comenzar el seguimiento.</p>
                    <a href="{{ route('psychological-followups.create', ['student_id' => $student->id]) }}" class="mt-4 inline-flex rounded-xl bg-club-primary px-4 py-2.5 text-sm font-bold text-white">Crear evaluación</a>
                </section>
            @else
                <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Última evaluación</p>
                        <p class="mt-2 text-xl font-black text-gray-900">{{ $latestEvaluation->evaluated_on->format('d/m/Y') }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Promedio general</p>
                        <p class="mt-2 text-3xl font-black text-club-primary">{{ number_format((float) $latestEvaluation->items->avg('rating'), 1) }} <span class="text-base text-gray-400">/ 5</span></p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Evaluado por</p>
                        <p class="mt-2 truncate text-lg font-black text-gray-900">{{ $latestEvaluation->evaluator_name }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Evaluaciones registradas</p>
                        <p class="mt-2 text-3xl font-black text-gray-900">{{ $evaluations->count() }}</p>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h3 class="font-black text-gray-900">Evolución general</h3>
                            <p class="mt-1 text-xs text-gray-500">Promedio por evaluación en los últimos seis meses.</p>
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                            <span>Lateralidad: <b class="text-gray-800">{{ $latestEvaluation->laterality ? ucfirst($latestEvaluation->laterality) : '—' }}</b></span>
                            <span>Somatotipo: <b class="text-gray-800">{{ $latestEvaluation->somatotype ? ucfirst($latestEvaluation->somatotype) : '—' }}</b></span>
                            <span>Talla: <b class="text-gray-800">{{ $latestEvaluation->height_cm ? $latestEvaluation->height_cm.' cm' : '—' }}</b></span>
                            <span>Peso: <b class="text-gray-800">{{ $latestEvaluation->weight_kg ? $latestEvaluation->weight_kg.' kg' : '—' }}</b></span>
                        </div>
                    </div>
                    <div class="mt-5 grid grid-cols-6 gap-3">
                        @foreach($evolution as $month)
                            <div class="text-center">
                                <div class="flex h-28 items-end justify-center rounded-lg bg-gray-50 px-2">
                                    @if($month['average'] !== null)
                                        <div class="w-full rounded-t-md bg-club-primary/80" style="height: {{ max(8, ((float) $month['average'] / 5) * 100) }}%"></div>
                                    @else
                                        <span class="mb-3 text-lg text-gray-300">—</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-[11px] font-semibold text-gray-500">{{ $month['label'] }}</p>
                                @if($month['average'] !== null)
                                    <p class="text-xs font-black text-gray-700">{{ number_format((float) $month['average'], 1) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="grid gap-4 lg:grid-cols-2">
                    @foreach($blockSummaries as $block)
                        <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                                <h3 class="font-black text-gray-900">{{ $block['label'] }}</h3>
                                <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-sm font-black text-club-primary">
                                    {{ number_format((float) $block['average'], 1) }} / 5
                                </span>
                            </div>
                            <div class="divide-y divide-gray-50">
                                @foreach($block['items'] as $item)
                                    <div class="flex items-start justify-between gap-4 px-5 py-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $item['label'] }}</p>
                                            @if($item['comment'])
                                                <p class="mt-1 text-xs text-gray-500">{{ $item['comment'] }}</p>
                                            @endif
                                        </div>
                                        <span class="min-w-10 rounded-lg px-2 py-1 text-center text-sm font-black
                                            {{ $item['rating'] === 0 ? 'bg-gray-100 text-gray-600' : ($item['rating'] >= 4 ? 'bg-green-50 text-green-700' : ($item['rating'] <= 2 ? 'bg-amber-50 text-amber-700' : 'bg-gray-100 text-gray-700')) }}">
                                            {{ $item['rating'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </section>

                @if($latestEvaluation->conclusions || $latestEvaluation->proposal)
                    <section class="grid gap-4 sm:grid-cols-2">
                        @if($latestEvaluation->conclusions)
                            <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                                <h3 class="font-black text-gray-900"><i class="bi bi-journal-text mr-2 text-club-primary"></i>Conclusiones</h3>
                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $latestEvaluation->conclusions }}</p>
                            </article>
                        @endif
                        @if($latestEvaluation->proposal)
                            <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                                <h3 class="font-black text-gray-900"><i class="bi bi-signpost-split mr-2 text-club-primary"></i>Propuesta de trabajo</h3>
                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $latestEvaluation->proposal }}</p>
                            </article>
                        @endif
                    </section>
                @endif
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-black text-gray-900">Historial de evaluaciones</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($evaluations as $evaluation)
                        <details class="group px-5 py-4">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $evaluation->evaluated_on->format('d/m/Y') }}</p>
                                    <p class="mt-1 text-xs text-gray-500">Evaluador: {{ $evaluation->evaluator_name }}@if($evaluation->coach_name) · Entrenador: {{ $evaluation->coach_name }}@endif</p>
                                </div>
                                <span class="flex items-center gap-3">
                                    <b class="text-club-primary">{{ number_format((float) $evaluation->items->avg('rating'), 1) }} / 5</b>
                                    <i class="bi bi-chevron-down text-gray-400 transition group-open:rotate-180"></i>
                                </span>
                            </summary>
                            <div class="mt-4 grid gap-4 border-t border-gray-100 pt-4 sm:grid-cols-2">
                                @foreach($blocks as $block)
                                    <div>
                                        <h4 class="mb-2 text-xs font-black uppercase tracking-wide text-gray-500">{{ $block['label'] }}</h4>
                                        <div class="space-y-1">
                                            @foreach($block['criteria'] as $criterionCode => $criterionLabel)
                                                @php($item = $evaluation->items->firstWhere('criterion_code', $criterionCode))
                                                <p class="flex justify-between gap-3 text-xs text-gray-600">
                                                    <span>{{ $criterionLabel }}</span><b class="shrink-0 text-gray-900">{{ $item?->rating ?? '—' }}</b>
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                @if($evaluation->conclusions || $evaluation->proposal)
                                    <div class="sm:col-span-2">
                                        @if($evaluation->conclusions)<p class="whitespace-pre-line text-sm text-gray-600"><b>Conclusiones:</b> {{ $evaluation->conclusions }}</p>@endif
                                        @if($evaluation->proposal)<p class="mt-2 whitespace-pre-line text-sm text-gray-600"><b>Propuesta:</b> {{ $evaluation->proposal }}</p>@endif
                                    </div>
                                @endif
                            </div>
                        </details>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-gray-500">Todavía no hay evaluaciones en el historial.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black text-gray-900">Seguimiento psicológico</h2>
                <p class="mt-1 text-sm text-gray-500">Evaluación y evolución confidencial de los deportistas.</p>
            </div>
            <a href="{{ route('psychological-followups.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-club-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                <i class="bi bi-plus-lg"></i> Nueva evaluación
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Deportistas activos</p>
                    <p class="mt-2 text-3xl font-black text-gray-900">{{ $totalStudents }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Evaluaciones este mes</p>
                    <p class="mt-2 text-3xl font-black text-gray-900">{{ $evaluationsThisMonth }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Sin evaluación este mes</p>
                    <p class="mt-2 text-3xl font-black text-amber-600">{{ $studentsWithoutReview }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Promedio general (0–5)</p>
                    <p class="mt-2 text-3xl font-black text-club-primary">
                        {{ $averageRating !== null ? number_format((float) $averageRating, 1) : '—' }}
                    </p>
                </div>
            </div>

            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 p-5 sm:p-6">
                    <form method="GET" class="grid gap-3 sm:grid-cols-[1fr_220px_auto]">
                        <label class="sr-only" for="search">Buscar deportista</label>
                        <input id="search" name="search" value="{{ $search }}"
                               placeholder="Buscar por nombre o documento"
                               class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                        <label class="sr-only" for="category">Filtrar por categoría</label>
                        <select id="category" name="category"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-club-primary focus:ring-club-primary">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        <button class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">Filtrar</button>
                    </form>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        @php($evaluation = $student->latestPsychologicalEvaluation)
                        <a href="{{ route('psychological-followups.show', $student) }}"
                           class="flex flex-col gap-3 px-5 py-4 transition hover:bg-gray-50 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-lg font-black text-club-primary">
                                    {{ strtoupper(mb_substr($student->nomDeportista, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-gray-900">{{ $student->nomDeportista }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->Categoria }} · Doc. {{ $student->numDocumento }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-5 sm:justify-end">
                                <div class="text-left sm:text-right">
                                    <p class="text-xs font-semibold text-gray-500">
                                        {{ $evaluation ? 'Última evaluación: '.$evaluation->evaluated_on->format('d/m/Y') : 'Sin evaluaciones' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $student->psychological_evaluations_count }}
                                        {{ $student->psychological_evaluations_count === 1 ? 'evaluación' : 'evaluaciones' }}
                                    </p>
                                </div>
                                @if($evaluation)
                                    <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-black text-club-primary">
                                        {{ number_format((float) $evaluation->items->avg('rating'), 1) }} / 5
                                    </span>
                                @endif
                                <i class="bi bi-chevron-right text-gray-300"></i>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-14 text-center">
                            <i class="bi bi-person-lines-fill text-4xl text-gray-300"></i>
                            <p class="mt-3 font-bold text-gray-700">No hay deportistas para mostrar</p>
                            <p class="mt-1 text-sm text-gray-500">Prueba con otros filtros o registra deportistas activos en el club.</p>
                        </div>
                    @endforelse
                </div>
                @if($students->hasPages())
                    <div class="border-t border-gray-100 p-4">{{ $students->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>

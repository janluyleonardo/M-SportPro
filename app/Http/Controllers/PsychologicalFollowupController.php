<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePsychologicalEvaluationRequest;
use App\Models\PsychologicalEvaluation;
use App\Models\PsychologicalEvaluationItem;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PsychologicalFollowupController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizePsychologicalAccess();

        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();
        $search = trim((string) $request->query('search', ''));
        $category = $request->query('category');

        $studentsQuery = Student::query()
            ->active()
            ->with('latestPsychologicalEvaluation.items')
            ->withCount('psychologicalEvaluations')
            ->orderBy('nomDeportista');

        if ($search !== '') {
            $studentsQuery->where(function (Builder $query) use ($search) {
                $query->where('nomDeportista', 'like', "%{$search}%")
                    ->orWhere('numDocumento', 'like', "%{$search}%");
            });
        }

        if (is_string($category) && $category !== '') {
            $studentsQuery->where('Categoria', $category);
        }

        $students = $studentsQuery->paginate(12)->withQueryString();

        $evaluationsThisMonth = PsychologicalEvaluation::query()
            ->whereBetween('evaluated_on', [$monthStart, $monthEnd]);

        $studentsWithoutReview = Student::query()
            ->active()
            ->whereDoesntHave('psychologicalEvaluations', function (Builder $query) use ($monthStart, $monthEnd) {
                $query->whereBetween('evaluated_on', [$monthStart, $monthEnd]);
            })
            ->count();

        $categories = Student::query()
            ->active()
            ->whereNotNull('Categoria')
            ->distinct()
            ->orderBy('Categoria')
            ->pluck('Categoria');

        return view('psychological-followups.index', [
            'students' => $students,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $category,
            'totalStudents' => Student::active()->count(),
            'evaluationsThisMonth' => $evaluationsThisMonth->count(),
            'studentsWithoutReview' => $studentsWithoutReview,
            'averageRating' => PsychologicalEvaluationItem::query()
                ->whereHas('evaluation')
                ->avg('rating'),
        ]);
    }

    public function create(): View
    {
        $this->authorizePsychologicalAccess();

        $students = Student::query()
            ->active()
            ->orderBy('nomDeportista')
            ->get(['id', 'nomDeportista', 'Categoria', 'numDocumento']);

        return view('psychological-followups.create', [
            'students' => $students,
            'blocks' => config('psychological_followup.blocks'),
        ]);
    }

    public function store(StorePsychologicalEvaluationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $blocks = config('psychological_followup.blocks');

        $evaluation = DB::transaction(function () use ($data, $user, $blocks) {
            $evaluation = PsychologicalEvaluation::create([
                'club_id' => $user->club_id,
                'student_id' => $data['student_id'],
                'evaluator_id' => $user->id,
                'evaluator_name' => $user->name,
                'coach_name' => $data['coach_name'] ?? null,
                'evaluated_on' => $data['evaluated_on'],
                'laterality' => $data['laterality'] ?? null,
                'somatotype' => $data['somatotype'] ?? null,
                'height_cm' => $data['height_cm'] ?? null,
                'weight_kg' => $data['weight_kg'] ?? null,
                'conclusions' => $data['conclusions'] ?? null,
                'proposal' => $data['proposal'] ?? null,
            ]);

            $criterionLabels = collect($blocks)
                ->flatMap(fn (array $block) => $block['criteria'])
                ->all();

            foreach ($criterionLabels as $code => $label) {
                $evaluation->items()->create([
                    'criterion_code' => $code,
                    'rating' => $data['criteria'][$code]['rating'],
                    'comment' => $data['criteria'][$code]['comment'] ?? null,
                ]);
            }

            return $evaluation;
        });

        return redirect()
            ->route('psychological-followups.show', $evaluation->student_id)
            ->with('success', 'La evaluación se guardó correctamente.');
    }

    public function show(Student $student): View
    {
        $this->authorizePsychologicalAccess();
        $clubId = auth()->user()->club_id;
        abort_unless($clubId && (int) $student->club_id === (int) $clubId, 404);

        $evaluations = $student->psychologicalEvaluations()
            ->with(['items', 'evaluator'])
            ->orderByDesc('evaluated_on')
            ->orderByDesc('id')
            ->get();
        $latestEvaluation = $evaluations->first();
        $blocks = config('psychological_followup.blocks');

        $blockSummaries = collect($blocks)->map(function (array $block, string $blockCode) use ($latestEvaluation) {
            $items = collect($block['criteria'])->map(function (string $label, string $criterionCode) use ($latestEvaluation) {
                $item = $latestEvaluation?->items->firstWhere('criterion_code', $criterionCode);

                return [
                    'code' => $criterionCode,
                    'label' => $label,
                    'rating' => $item?->rating,
                    'comment' => $item?->comment,
                ];
            });

            return [
                'code' => $blockCode,
                'label' => $block['label'],
                'average' => $items->avg('rating'),
                'items' => $items,
            ];
        });

        $chartEvaluations = $evaluations
            ->filter(fn (PsychologicalEvaluation $evaluation) => $evaluation->evaluated_on->gte(now()->subMonths(5)->startOfMonth()))
            ->groupBy(fn (PsychologicalEvaluation $evaluation) => $evaluation->evaluated_on->format('Y-m'));

        $evolution = collect(range(5, 0))->map(function (int $monthsAgo) use ($chartEvaluations) {
            $month = now()->startOfMonth()->subMonths($monthsAgo);
            $monthEvaluations = $chartEvaluations->get($month->format('Y-m'), collect());

            return [
                'label' => $month->translatedFormat('M y'),
                'average' => $monthEvaluations->avg(fn (PsychologicalEvaluation $evaluation) => $evaluation->items->avg('rating')),
            ];
        });

        return view('psychological-followups.show', [
            'student' => $student,
            'evaluations' => $evaluations,
            'latestEvaluation' => $latestEvaluation,
            'blockSummaries' => $blockSummaries,
            'evolution' => $evolution,
            'blocks' => $blocks,
        ]);
    }

    private function authorizePsychologicalAccess(): void
    {
        abort_unless(
            !auth()->user()->is_super_admin && auth()->user()->hasAnyRole(['Admin', 'Psicólogo']),
            403
        );
    }
}

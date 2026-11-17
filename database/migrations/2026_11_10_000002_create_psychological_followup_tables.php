<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('psychological_evaluations')) {
            Schema::create('psychological_evaluations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('club_id')->constrained()->cascadeOnDelete();
                $table->foreignId('student_id')->constrained()->restrictOnDelete();
                $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('evaluator_name');
                $table->string('coach_name')->nullable();
                $table->date('evaluated_on');
                $table->enum('laterality', ['diestro', 'zurdo', 'ambidiestro'])->nullable();
                $table->enum('somatotype', ['ectomorfo', 'mesomorfo', 'endomorfo'])->nullable();
                $table->decimal('height_cm', 5, 2)->nullable();
                $table->decimal('weight_kg', 5, 2)->nullable();
                $table->text('conclusions')->nullable();
                $table->text('proposal')->nullable();
                $table->timestamps();

                $table->index(['club_id', 'evaluated_on']);
                $table->index(['club_id', 'student_id', 'evaluated_on']);
            });
        }

        if (! Schema::hasTable('psychological_evaluation_items')) {
            Schema::create('psychological_evaluation_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('evaluation_id')
                    ->constrained('psychological_evaluations')
                    ->cascadeOnDelete();
                $table->string('criterion_code', 80);
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique(
                    ['evaluation_id', 'criterion_code'],
                    'psych_eval_items_eval_criterion_unique'
                );
            });
        } elseif (! Schema::hasIndex('psychological_evaluation_items', 'psych_eval_items_eval_criterion_unique', 'unique')) {
            Schema::table('psychological_evaluation_items', function (Blueprint $table) {
                $table->unique(
                    ['evaluation_id', 'criterion_code'],
                    'psych_eval_items_eval_criterion_unique'
                );
            });
        }

        DB::table('roles')->updateOrInsert(
            ['name' => 'Psicólogo', 'guard_name' => 'web'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        DB::table('modules')->updateOrInsert(
            ['slug' => 'psychological-followup'],
            [
                'name' => 'Seguimiento psicológico',
                'description' => 'Evaluación y seguimiento confidencial del proceso de los deportistas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('psychological_evaluation_items');
        Schema::dropIfExists('psychological_evaluations');

        $moduleId = DB::table('modules')->where('slug', 'psychological-followup')->value('id');
        if ($moduleId) {
            DB::table('club_module')->where('module_id', $moduleId)->delete();
            DB::table('modules')->where('id', $moduleId)->delete();
        }

        $roleId = DB::table('roles')
            ->where('name', 'Psicólogo')
            ->where('guard_name', 'web')
            ->value('id');
        if ($roleId) {
            DB::table('model_has_roles')->where('role_id', $roleId)->delete();
            DB::table('role_has_permissions')->where('role_id', $roleId)->delete();
            DB::table('roles')->where('id', $roleId)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};

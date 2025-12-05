<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('project_division_id')->constrained('project_divisions')->cascadeOnDelete();
            $table->foreignUuid('task_catalog_id')->constrained('task_catalog');
            $table->string('display_name', 250);
            $table->integer('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('row_version')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('project_division_id');
            $table->index('task_catalog_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_line_items', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('project_task_id')->constrained('project_tasks')->cascadeOnDelete();
            $table->foreignUuid('item_catalog_id')->constrained('item_catalog');
            $table->string('description', 300)->nullable();
            $table->foreignUuid('unit_id')->constrained('units');
            $table->decimal('quantity', 18, 4)->default(0);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('line_total', 18, 2)->storedAs('quantity * unit_price');
            $table->boolean('taxable')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('project_task_id');
            $table->index('item_catalog_id');
            $table->unique(['project_task_id', 'item_catalog_id', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_line_items');
    }
};

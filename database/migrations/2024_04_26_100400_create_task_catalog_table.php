<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_catalog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('division_id')->constrained('work_division_catalog');
            $table->string('code', 80);
            $table->string('name', 250);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('division_id');
            $table->unique(['division_id', 'code']);
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX task_catalog_division_lower_code_unique ON task_catalog (division_id, LOWER(code));');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('task_catalog');
    }
};

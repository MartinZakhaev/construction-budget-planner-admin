<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_catalog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->enum('type', ['MATERIAL', 'MANPOWER', 'TOOL']);
            $table->string('code', 80)->unique();
            $table->string('name', 250);
            $table->foreignUuid('unit_id')->constrained('units');
            $table->decimal('default_price', 18, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('unit_id');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX item_catalog_lower_code_unique ON item_catalog (LOWER(code));');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('item_catalog');
    }
};

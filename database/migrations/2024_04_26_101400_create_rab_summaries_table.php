<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rab_summaries', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->integer('version')->default(1);
            $table->decimal('subtotal_material', 18, 2)->default(0);
            $table->decimal('subtotal_manpower', 18, 2)->default(0);
            $table->decimal('subtotal_tools', 18, 2)->default(0);
            $table->decimal('taxable_subtotal', 18, 2)->default(0);
            $table->decimal('nontax_subtotal', 18, 2)->default(0);
            $table->decimal('tax_rate_percent', 5, 2)->default(11.00);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('grand_total', 18, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignUuid('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->unique(['project_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_summaries');
    }
};

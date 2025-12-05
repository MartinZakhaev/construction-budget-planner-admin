<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rab_exports', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('rab_summary_id')->constrained('rab_summaries')->cascadeOnDelete();
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('pdf_file_id')->nullable()->constrained('files')->nullOnDelete();
            $table->foreignUuid('xlsx_file_id')->nullable()->constrained('files')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('rab_summary_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_exports');
    }
};

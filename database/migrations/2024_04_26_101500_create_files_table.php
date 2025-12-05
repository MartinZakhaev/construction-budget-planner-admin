<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('owner_user_id')->constrained('users');
            $table->foreignUuid('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->enum('kind', ['AVATAR', 'IMAGE', 'SITE_PLAN', 'DOCUMENT', 'OTHER'])->default('OTHER');
            $table->string('filename', 300);
            $table->string('mime_type', 150)->nullable();
            $table->bigInteger('size_bytes')->nullable();
            $table->text('storage_path');
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('owner_user_id');
            $table->index('kind');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('profile_file_id')->references('id')->on('files')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_file_id']);
        });

        Schema::dropIfExists('files');
    }
};

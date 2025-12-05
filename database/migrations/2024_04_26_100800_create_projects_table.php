<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('organization_id')->constrained('organizations');
            $table->foreignUuid('owner_user_id')->constrained('users');
            $table->string('name', 250);
            $table->string('code', 80)->nullable();
            $table->text('description')->nullable();
            $table->string('location', 250)->nullable();
            $table->decimal('tax_rate_percent', 5, 2)->default(11.00);
            $table->string('currency', 10)->default('IDR');
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('owner_user_id');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement("CREATE UNIQUE INDEX projects_owner_user_lower_code_unique ON projects (owner_user_id, LOWER(code)) WHERE code IS NOT NULL;");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

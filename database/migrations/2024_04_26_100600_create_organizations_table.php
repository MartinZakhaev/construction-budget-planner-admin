<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name', 200);
            $table->string('code', 80)->nullable()->unique();
            $table->foreignUuid('owner_user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_user_id');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX organizations_code_lower_unique ON organizations (LOWER(code)) WHERE code IS NOT NULL;');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};

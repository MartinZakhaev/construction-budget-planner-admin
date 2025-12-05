<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('code', 50)->unique();
            $table->string('name', 120);
            $table->integer('price_cents');
            $table->string('currency', 10)->default('IDR');
            $table->string('interval', 20)->default('monthly');
            $table->integer('max_projects')->default(10);
            $table->timestamps();
            $table->softDeletes();  
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

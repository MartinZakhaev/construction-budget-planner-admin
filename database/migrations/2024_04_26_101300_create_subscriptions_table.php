<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('plan_id')->constrained('plans');
            $table->enum('status', ['TRIALING', 'ACTIVE', 'PAST_DUE', 'CANCELED', 'EXPIRED'])->default('ACTIVE');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('plan_id');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement("CREATE UNIQUE INDEX subscriptions_user_active_status_unique ON subscriptions (user_id) WHERE status IN ('TRIALING', 'ACTIVE', 'PAST_DUE');");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'owner@terra.corp')->first();
        $plan = Plan::query()->where('code', 'PRO')->first();

        if (! $user || ! $plan) {
            return;
        }

        Subscription::query()->updateOrCreate(
            ['user_id' => $user->id, 'plan_id' => $plan->id],
            [
                'status' => 'ACTIVE',
                'trial_ends_at' => Carbon::now()->addDays(14),
                'current_period_start' => Carbon::now()->startOfMonth(),
                'current_period_end' => Carbon::now()->endOfMonth(),
            ],
        );
    }
}

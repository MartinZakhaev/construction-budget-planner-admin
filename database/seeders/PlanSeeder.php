<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'BASIC',
                'name' => 'Basic Workspace',
                'price_cents' => 2500000,
                'currency' => 'IDR',
                'interval' => 'monthly',
                'max_projects' => 5,
            ],
            [
                'code' => 'PRO',
                'name' => 'Professional Suite',
                'price_cents' => 7500000,
                'currency' => 'IDR',
                'interval' => 'monthly',
                'max_projects' => 25,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['code' => $plan['code']],
                $plan,
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['code' => 'M3', 'name' => 'Cubic Meter'],
            ['code' => 'PCS', 'name' => 'Pieces'],
            ['code' => 'HRS', 'name' => 'Hours'],
        ];

        foreach ($units as $unit) {
            Unit::query()->updateOrCreate(
                ['code' => $unit['code']],
                $unit,
            );
        }
    }
}

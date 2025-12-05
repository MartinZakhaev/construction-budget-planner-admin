<?php

namespace Database\Seeders;

use App\Models\ItemCatalog;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ItemCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $unitMap = Unit::query()->pluck('id', 'code');

        $items = [
            ['type' => 'MATERIAL', 'code' => 'CEMENT', 'name' => 'Cement Bag', 'unit_code' => 'PCS', 'default_price' => 60000],
            ['type' => 'MATERIAL', 'code' => 'SAND', 'name' => 'Sand m3', 'unit_code' => 'M3', 'default_price' => 150000],
            ['type' => 'MANPOWER', 'code' => 'SKILLED', 'name' => 'Skilled Worker', 'unit_code' => 'HRS', 'default_price' => 55000],
            ['type' => 'MANPOWER', 'code' => 'HELPER', 'name' => 'Helper', 'unit_code' => 'HRS', 'default_price' => 35000],
            ['type' => 'TOOL', 'code' => 'MIXER', 'name' => 'Concrete Mixer Rental', 'unit_code' => 'HRS', 'default_price' => 120000],
            ['type' => 'TOOL', 'code' => 'CUTTER', 'name' => 'Steel Cutter Rental', 'unit_code' => 'HRS', 'default_price' => 90000],
        ];

        foreach ($items as $item) {
            $unitId = $unitMap[$item['unit_code']] ?? null;
            if (! $unitId) {
                continue;
            }

            ItemCatalog::query()->updateOrCreate(
                ['code' => $item['code']],
                [
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'unit_id' => $unitId,
                    'default_price' => $item['default_price'],
                ],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\WorkDivisionCatalog;
use Illuminate\Database\Seeder;

class WorkDivisionCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['code' => 'FOUNDATION', 'name' => 'Pekerjaan Pondasi', 'description' => 'Earthworks, pile, and footings'],
            ['code' => 'STRUCTURE', 'name' => 'Pekerjaan Struktur', 'description' => 'Columns, beams, slabs'],
            ['code' => 'FINISHING', 'name' => 'Pekerjaan Finishing', 'description' => 'Architectural finishes'],
        ];

        foreach ($divisions as $division) {
            WorkDivisionCatalog::query()->updateOrCreate(
                ['code' => $division['code']],
                $division,
            );
        }
    }
}

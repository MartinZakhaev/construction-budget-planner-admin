<?php

namespace Database\Seeders;

use App\Models\TaskCatalog;
use App\Models\WorkDivisionCatalog;
use Illuminate\Database\Seeder;

class TaskCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            'FOUNDATION' => [
                ['code' => 'EXCAV', 'name' => 'Excavation', 'description' => 'Manual and mechanical digging'],
                ['code' => 'REBAR', 'name' => 'Rebar Installation', 'description' => 'Footing reinforcement'],
            ],
            'STRUCTURE' => [
                ['code' => 'FORMWORK', 'name' => 'Formwork Assembly'],
                ['code' => 'CONCRETE', 'name' => 'Concrete Pouring'],
            ],
            'FINISHING' => [
                ['code' => 'PLASTER', 'name' => 'Wall Plaster'],
                ['code' => 'PAINT', 'name' => 'Painting'],
            ],
        ];

        foreach ($tasks as $divisionCode => $items) {
            $division = WorkDivisionCatalog::query()->where('code', $divisionCode)->first();
            if (! $division) {
                continue;
            }

            foreach ($items as $task) {
                TaskCatalog::query()->updateOrCreate(
                    [
                        'division_id' => $division->id,
                        'code' => $task['code'],
                    ],
                    [
                        'name' => $task['name'],
                        'description' => $task['description'] ?? null,
                    ],
                );
            }
        }
    }
}

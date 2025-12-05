<?php

namespace Database\Seeders;

use App\Models\ItemCatalog;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\TaskCatalog;
use App\Models\TaskLineItem;
use Illuminate\Database\Seeder;

class TaskLineItemSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        if (! $project) {
            return;
        }

        $taskByCatalog = ProjectTask::query()
            ->where('project_id', $project->id)
            ->get()
            ->keyBy('task_catalog_id');

        $taskCatalogByCode = TaskCatalog::query()->pluck('id', 'code');
        $items = ItemCatalog::query()->get()->keyBy('code');

        $definitions = [
            [
                'task_code' => 'EXCAV',
                'lines' => [
                    ['item_code' => 'SAND', 'description' => 'Backfill material', 'quantity' => 12, 'unit_price' => 160000],
                    ['item_code' => 'MIXER', 'description' => 'Excavator rental (hrs)', 'quantity' => 30, 'unit_price' => 125000],
                    ['item_code' => 'SKILLED', 'description' => 'Excavation crew', 'quantity' => 60, 'unit_price' => 60000],
                ],
            ],
            [
                'task_code' => 'CONCRETE',
                'lines' => [
                    ['item_code' => 'CEMENT', 'description' => 'Structural concrete mix', 'quantity' => 200, 'unit_price' => 65000],
                    ['item_code' => 'MIXER', 'description' => 'Concrete mixer usage', 'quantity' => 45, 'unit_price' => 120000],
                    ['item_code' => 'HELPER', 'description' => 'Helpers for casting', 'quantity' => 80, 'unit_price' => 40000],
                ],
            ],
            [
                'task_code' => 'PAINT',
                'lines' => [
                    ['item_code' => 'HELPER', 'description' => 'Painter assistants', 'quantity' => 50, 'unit_price' => 38000],
                    ['item_code' => 'SKILLED', 'description' => 'Senior painters', 'quantity' => 30, 'unit_price' => 65000],
                ],
            ],
        ];

        foreach ($definitions as $definition) {
            $taskCatalogId = $taskCatalogByCode[$definition['task_code']] ?? null;
            if (! $taskCatalogId) {
                continue;
            }

            $projectTask = $taskByCatalog[$taskCatalogId] ?? null;
            if (! $projectTask) {
                continue;
            }

            foreach ($definition['lines'] as $line) {
                $item = $items[$line['item_code']] ?? null;
                if (! $item) {
                    continue;
                }

                TaskLineItem::query()->updateOrCreate(
                    [
                        'project_task_id' => $projectTask->id,
                        'item_catalog_id' => $item->id,
                        'description' => $line['description'],
                    ],
                    [
                        'project_id' => $project->id,
                        'unit_id' => $item->unit_id,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['unit_price'],
                        'taxable' => true,
                    ],
                );
            }
        }
    }
}

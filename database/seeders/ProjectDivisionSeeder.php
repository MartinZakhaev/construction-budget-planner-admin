<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectDivision;
use App\Models\WorkDivisionCatalog;
use Illuminate\Database\Seeder;

class ProjectDivisionSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        $divisionCatalog = WorkDivisionCatalog::query()->pluck('id', 'code');

        if (! $project || $divisionCatalog->isEmpty()) {
            return;
        }

        $orderedCodes = ['FOUNDATION', 'STRUCTURE', 'FINISHING'];

        foreach ($orderedCodes as $index => $code) {
            $catalogId = $divisionCatalog[$code] ?? null;
            if (! $catalogId) {
                continue;
            }

            ProjectDivision::query()->updateOrCreate(
                [
                    'project_id' => $project->id,
                    'division_id' => $catalogId,
                ],
                [
                    'display_name' => ucfirst(strtolower($code)) . ' Division',
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectDivision;
use App\Models\ProjectTask;
use App\Models\TaskCatalog;
use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        if (! $project) {
            return;
        }

        $divisionMap = ProjectDivision::query()
            ->where('project_id', $project->id)
            ->pluck('id', 'division_id');

        $tasksByDivision = TaskCatalog::query()
            ->get()
            ->groupBy('division_id');

        foreach ($tasksByDivision as $divisionId => $tasks) {
            $projectDivisionId = $divisionMap[$divisionId] ?? null;
            if (! $projectDivisionId) {
                continue;
            }

            foreach ($tasks as $index => $taskCatalog) {
                ProjectTask::query()->updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'project_division_id' => $projectDivisionId,
                        'task_catalog_id' => $taskCatalog->id,
                    ],
                    [
                        'display_name' => $taskCatalog->name,
                        'sort_order' => $index + 1,
                        'notes' => $taskCatalog->description,
                        'row_version' => $index + 1,
                    ],
                );
            }
        }
    }
}

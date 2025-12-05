<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Project;
use App\Models\RabExport;
use App\Models\RabSummary;
use Illuminate\Database\Seeder;

class RabExportSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        $summary = RabSummary::query()->where('project_id', optional($project)->id)->first();
        $file = File::query()->where('filename', 'green-tower-site-plan.pdf')->first();

        if (! $project || ! $summary) {
            return;
        }

        RabExport::query()->updateOrCreate(
            ['rab_summary_id' => $summary->id],
            [
                'project_id' => $project->id,
                'pdf_file_id' => optional($file)->id,
                'xlsx_file_id' => null,
            ],
        );
    }
}

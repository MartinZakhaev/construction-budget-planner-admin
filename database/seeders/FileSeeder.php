<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        $owner = User::query()->where('email', 'owner@terra.corp')->first();

        if (! $project || ! $owner) {
            return;
        }

        File::query()->updateOrCreate(
            ['filename' => 'green-tower-site-plan.pdf'],
            [
                'owner_user_id' => $owner->id,
                'project_id' => $project->id,
                'kind' => 'SITE_PLAN',
                'mime_type' => 'application/pdf',
                'size_bytes' => 1024 * 1024,
                'storage_path' => 'documents/green-tower-site-plan.pdf',
            ],
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCollaborator;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectCollaboratorSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        $user = User::query()->where('email', 'collaborator@terra.corp')->first();

        if (! $project || ! $user) {
            return;
        }

        ProjectCollaborator::query()->updateOrCreate(
            [
                'project_id' => $project->id,
                'user_id' => $user->id,
            ],
            ['role' => 'EDITOR'],
        );
    }
}

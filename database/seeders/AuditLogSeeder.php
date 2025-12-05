<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        $user = User::query()->where('email', 'auditor@terra.corp')->first();

        AuditLog::query()->firstOrCreate(
            [
                'action' => 'project.rab.generated',
                'project_id' => optional($project)->id,
            ],
            [
                'user_id' => optional($user)->id,
                'entity_table' => 'rab_summaries',
                'entity_id' => null,
                'meta' => ['message' => 'Seeded RAB summary approval'],
                'ip' => '127.0.0.1',
                'user_agent' => 'Seeder/1.0',
            ],
        );
    }
}

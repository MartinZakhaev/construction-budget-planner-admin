<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::query()->where('code', 'TERRA-BUILD')->first();
        $owner = User::query()->where('email', 'owner@terra.corp')->first();

        if (! $organization || ! $owner) {
            return;
        }

        Project::query()->updateOrCreate(
            ['code' => 'GREEN-TOWER'],
            [
                'organization_id' => $organization->id,
                'owner_user_id' => $owner->id,
                'name' => 'Green Tower Residence',
                'description' => 'High-rise residential development.',
                'location' => 'Jakarta, Indonesia',
                'tax_rate_percent' => 11.00,
                'currency' => 'IDR',
            ],
        );
    }
}

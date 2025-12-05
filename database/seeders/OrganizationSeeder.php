<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->where('email', 'owner@terra.corp')->first();

        if (! $owner) {
            return;
        }

        Organization::query()->updateOrCreate(
            ['code' => 'TERRA-BUILD'],
            [
                'name' => 'Terra Build Corp',
                'owner_user_id' => $owner->id,
            ],
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationMemberSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::query()->where('code', 'TERRA-BUILD')->first();
        $users = User::query()->whereIn('email', [
            'owner@terra.corp',
            'collaborator@terra.corp',
        ])->get()->keyBy('email');

        if (! $organization || $users->isEmpty()) {
            return;
        }

        $entries = [
            ['email' => 'owner@terra.corp', 'role' => 'OWNER'],
            ['email' => 'collaborator@terra.corp', 'role' => 'EDITOR'],
        ];

        foreach ($entries as $entry) {
            $user = $users[$entry['email']] ?? null;
            if (! $user) {
                continue;
            }

            OrganizationMember::query()->updateOrCreate(
                [
                    'organization_id' => $organization->id,
                    'user_id' => $user->id,
                ],
                ['role' => $entry['role']],
            );
        }
    }
}

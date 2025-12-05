<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PlanSeeder::class,
            SubscriptionSeeder::class,
            UnitSeeder::class,
            WorkDivisionCatalogSeeder::class,
            TaskCatalogSeeder::class,
            ItemCatalogSeeder::class,
            OrganizationSeeder::class,
            OrganizationMemberSeeder::class,
            ProjectSeeder::class,
            ProjectCollaboratorSeeder::class,
            ProjectDivisionSeeder::class,
            ProjectTaskSeeder::class,
            TaskLineItemSeeder::class,
            RabSummarySeeder::class,
            FileSeeder::class,
            RabExportSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}

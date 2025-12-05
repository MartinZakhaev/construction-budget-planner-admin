<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\RabSummary;
use App\Models\TaskLineItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class RabSummarySeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::query()->where('code', 'GREEN-TOWER')->first();
        $creator = User::query()->where('email', 'owner@terra.corp')->first();

        if (! $project || ! $creator) {
            return;
        }

        $lineItems = TaskLineItem::query()
            ->where('project_id', $project->id)
            ->with('itemCatalog')
            ->get();

        if ($lineItems->isEmpty()) {
            return;
        }

        $totals = [
            'MATERIAL' => 0,
            'MANPOWER' => 0,
            'TOOL' => 0,
        ];

        $taxableSubtotal = 0;
        $nonTaxSubtotal = 0;

        foreach ($lineItems as $lineItem) {
            $lineTotal = (float) ($lineItem->quantity * $lineItem->unit_price);
            $type = $lineItem->itemCatalog->type ?? 'MATERIAL';
            $totals[$type] = ($totals[$type] ?? 0) + $lineTotal;

            if ($lineItem->taxable) {
                $taxableSubtotal += $lineTotal;
            } else {
                $nonTaxSubtotal += $lineTotal;
            }
        }

        $taxRate = $project->tax_rate_percent ?? 11.00;
        $taxAmount = $taxableSubtotal * ($taxRate / 100);
        $grandTotal = $taxableSubtotal + $nonTaxSubtotal + $taxAmount;

        RabSummary::query()->updateOrCreate(
            [
                'project_id' => $project->id,
                'version' => 1,
            ],
            [
                'subtotal_material' => $totals['MATERIAL'],
                'subtotal_manpower' => $totals['MANPOWER'],
                'subtotal_tools' => $totals['TOOL'],
                'taxable_subtotal' => $taxableSubtotal,
                'nontax_subtotal' => $nonTaxSubtotal,
                'tax_rate_percent' => $taxRate,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'notes' => 'Auto-generated from seeded line items.',
                'created_by' => $creator->id,
            ],
        );
    }
}

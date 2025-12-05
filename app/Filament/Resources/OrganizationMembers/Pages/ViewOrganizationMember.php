<?php

namespace App\Filament\Resources\OrganizationMembers\Pages;

use App\Filament\Resources\OrganizationMembers\OrganizationMemberResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganizationMember extends ViewRecord
{
    protected static string $resource = OrganizationMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

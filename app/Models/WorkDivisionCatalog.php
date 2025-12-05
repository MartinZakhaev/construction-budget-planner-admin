<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkDivisionCatalog extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'work_division_catalog';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(TaskCatalog::class, 'division_id');
    }

    public function projectDivisions(): HasMany
    {
        return $this->hasMany(ProjectDivision::class, 'division_id');
    }
}

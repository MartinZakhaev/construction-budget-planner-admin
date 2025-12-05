<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTask extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'project_division_id',
        'task_catalog_id',
        'display_name',
        'sort_order',
        'notes',
        'row_version',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'row_version' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(ProjectDivision::class, 'project_division_id');
    }

    public function taskCatalog(): BelongsTo
    {
        return $this->belongsTo(TaskCatalog::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(TaskLineItem::class);
    }
}

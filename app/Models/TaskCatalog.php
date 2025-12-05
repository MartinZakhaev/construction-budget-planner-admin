<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskCatalog extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'task_catalog';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'division_id',
        'code',
        'name',
        'description',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(WorkDivisionCatalog::class, 'division_id');
    }

    public function projectTasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }
}

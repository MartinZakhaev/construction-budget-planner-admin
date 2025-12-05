<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'organization_id',
        'owner_user_id',
        'name',
        'code',
        'description',
        'location',
        'tax_rate_percent',
        'currency',
    ];

    protected $casts = [
        'tax_rate_percent' => 'decimal:2',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(ProjectCollaborator::class);
    }

    public function divisions(): HasMany
    {
        return $this->hasMany(ProjectDivision::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function taskLineItems(): HasMany
    {
        return $this->hasMany(TaskLineItem::class);
    }

    public function rabSummaries(): HasMany
    {
        return $this->hasMany(RabSummary::class);
    }

    public function rabExports(): HasMany
    {
        return $this->hasMany(RabExport::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}

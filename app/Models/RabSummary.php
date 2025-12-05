<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RabSummary extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'version',
        'subtotal_material',
        'subtotal_manpower',
        'subtotal_tools',
        'taxable_subtotal',
        'nontax_subtotal',
        'tax_rate_percent',
        'tax_amount',
        'grand_total',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'version' => 'integer',
        'subtotal_material' => 'decimal:2',
        'subtotal_manpower' => 'decimal:2',
        'subtotal_tools' => 'decimal:2',
        'taxable_subtotal' => 'decimal:2',
        'nontax_subtotal' => 'decimal:2',
        'tax_rate_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function exports(): HasMany
    {
        return $this->hasMany(RabExport::class);
    }
}

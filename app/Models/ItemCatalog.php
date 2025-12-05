<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCatalog extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'item_catalog';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'type',
        'code',
        'name',
        'unit_id',
        'default_price',
        'description',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function taskLineItems(): HasMany
    {
        return $this->hasMany(TaskLineItem::class);
    }
}

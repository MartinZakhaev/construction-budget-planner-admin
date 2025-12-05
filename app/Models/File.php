<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'files';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'owner_user_id',
        'project_id',
        'kind',
        'filename',
        'mime_type',
        'size_bytes',
        'storage_path',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'created_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function pdfExports(): HasMany
    {
        return $this->hasMany(RabExport::class, 'pdf_file_id');
    }

    public function xlsxExports(): HasMany
    {
        return $this->hasMany(RabExport::class, 'xlsx_file_id');
    }
}

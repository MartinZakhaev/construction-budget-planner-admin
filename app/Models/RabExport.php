<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RabExport extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'rab_summary_id',
        'project_id',
        'pdf_file_id',
        'xlsx_file_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function summary(): BelongsTo
    {
        return $this->belongsTo(RabSummary::class, 'rab_summary_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function pdfFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'pdf_file_id');
    }

    public function xlsxFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'xlsx_file_id');
    }
}

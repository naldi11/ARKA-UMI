<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisSupervisor extends Model
{
    /** @use HasFactory<\Database\Factories\ThesisSupervisorFactory> */
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'dosen_id',
        'type',
        'review_notes',
        'reviewed_at',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}

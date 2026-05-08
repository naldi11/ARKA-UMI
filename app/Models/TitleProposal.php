<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitleProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'title',
        'jurnal_name',
        'status',
        'rejection_reason',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}

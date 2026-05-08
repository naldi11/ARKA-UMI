<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    /** @use HasFactory<\Database\Factories\DosenFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_gelar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisions(): HasMany
    {
        return $this->hasMany(ThesisSupervisor::class);
    }
}

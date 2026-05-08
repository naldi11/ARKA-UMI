<?php

namespace App\Models;

use App\Models\Scopes\ThreeYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    /** @use HasFactory<\Database\Factories\MahasiswaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'phone',
        'avatar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thesis(): HasOne
    {
        return $this->hasOne(Thesis::class)->withoutGlobalScope(ThreeYearScope::class);
    }

    public function titleProposals(): HasMany
    {
        return $this->hasMany(TitleProposal::class);
    }
}

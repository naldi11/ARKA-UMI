<?php

namespace App\Models;

use App\Models\Scopes\ThreeYearScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Thesis extends Model
{
    /** @use HasFactory<\Database\Factories\ThesisFactory> */
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'title_proposal_id',
        'title',
        'jurnal_name',
        'status',
        'doc_skripsi',
        'doc_meja_hijau',
        'doc_jurnal',
        'doc_target_jurnal',
        'doc_sk_pembimbing_1',
        'doc_sk_pembimbing_2',
        'doc_izin_penelitian',
        'doc_cd',
        'doc_final',
        'admin_notes',
        'verification_data',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'verification_data' => 'array',
    ];

    /**
     * Booted function untuk menambahkan Global Scope otomatis.
     * Aktif untuk mahasiswa dan publik, Admin & Dosen bypass manual.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new ThreeYearScope);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function supervisors(): HasMany
    {
        return $this->hasMany(ThesisSupervisor::class);
    }

    /**
     * Override route model binding agar ThreeYearScope tidak memblokir
     * akses admin/dosen/mahasiswa ke thesis yang belum selesai (approved_at = null).
     * Scope tetap aktif untuk query publik biasa.
     */
    public function resolveRouteBinding($value, $field = null): static
    {
        return $this->withoutGlobalScope(ThreeYearScope::class)
                    ->where($field ?? $this->getRouteKeyName(), $value)
                    ->firstOrFail();
    }

    /**
     * Generate 5-minute signed url for secure download
     */
    public function getDownloadUrl($type)
    {
        if (!$this->{'doc_' . $type}) return '#';

        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'dokumen.download',
            now()->addMinutes(5),
            ['thesis' => $this->id, 'type' => $type]
        );
    }

    /**
     * Get the file extension of a specific document type.
     */
    public function getFileExtension($type): ?string
    {
        $field = 'doc_' . $type;
        $path = $this->$field;
        if (!$path) return null;

        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Hitung persentase progres upload dokumen (8 dokumen wajib)
     */
    public function calculateProgress(): int
    {
        return (int) (($this->uploadedCount() / 8) * 100);
    }

    /**
     * Batch 1: Dokumen Administrasi Awal
     */
    public function isBatch1Complete(): bool
    {
        return $this->doc_sk_pembimbing_1 && 
               $this->doc_sk_pembimbing_2 && 
               $this->doc_target_jurnal && 
               $this->doc_izin_penelitian;
    }

    /**
     * Hitung jumlah dokumen yang sudah diunggah
     */
    public function uploadedCount(): int
    {
        $fields = [
            'doc_sk_pembimbing_1', 'doc_sk_pembimbing_2', 'doc_target_jurnal', 'doc_izin_penelitian',
            'doc_jurnal', 'doc_skripsi', 'doc_meja_hijau', 'doc_cd'
        ];
        return collect($fields)->filter(fn($f) => $this->{$f})->count();
    }

    /**
     * Cek apakah semua dokumen wajib sudah terupload
     */
    public function allDocsUploaded(): bool
    {
        return $this->uploadedCount() === 8;
    }
}

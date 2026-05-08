<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ThreeYearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * Hanya menampilkan data yang memiliki approved_at dalam 3 tahun terakhir.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Tampilkan yang masih aktif (approved_at null) ATAU yang lulus dalam 3 tahun terakhir
        $builder->where(function($query) {
            $query->whereNull('approved_at')
                  ->orWhere('approved_at', '>=', now()->subYears(3));
        });
    }
}

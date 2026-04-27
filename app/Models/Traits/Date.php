<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Date
{
    public function scopeForDate(Builder $query, string $date): void
    {
        $query->where('date', $date);
    }
}

<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder|Model forExpeditor(int $expeditorId)
 */
trait Expeditor
{
    public static function scopeForExpeditor(Builder $query, int $expeditorId): void
    {
        $query->where('expeditor_id', $expeditorId);
    }
}

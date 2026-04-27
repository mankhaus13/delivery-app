<?php

namespace App\Models;

use App\Events\WorkDay\WorkDayChanged;
use App\Models\Traits\Date;
use App\Models\Traits\Expeditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Bodycheck
 *
 * @property int $id
 * @property string $date
 * @property string $time_start
 * @property int $expeditor_id
 * @property int $passed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $expeditor
 *
 * @method static \Database\Factories\BodycheckFactory factory($count = null, $state = [])
 * @method static Builder|Bodycheck forDate(string $date)
 * @method static Builder|Bodycheck forExpeditor(int $expeditorId)
 * @method static Builder|Bodycheck newModelQuery()
 * @method static Builder|Bodycheck newQuery()
 * @method static Builder|Bodycheck query()
 * @method static Builder|Bodycheck whereCreatedAt($value)
 * @method static Builder|Bodycheck whereExpeditorId($value)
 * @method static Builder|Bodycheck whereId($value)
 * @method static Builder|Bodycheck wherePassed($value)
 * @method static Builder|Bodycheck whereStartsAt($value)
 * @method static Builder|Bodycheck whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Bodycheck extends Model
{
    use Expeditor;
    use Date;
    use HasFactory;

    protected $fillable = [
        'date',
        'time_start',
        'passed',
        'expeditor_id',
    ];

    protected $dispatchesEvents = [
        'saved' => WorkDayChanged::class,
    ];

    public function expeditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expeditor_id', 'id');
    }

    public function scopeForDate(Builder $query, string $date): void
    {
        $query->where('date', $date);
    }
}

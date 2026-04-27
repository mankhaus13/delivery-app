<?php

namespace App\Models;

use App\Events\WorkDay\WorkDayChanged;
use App\Models\Enums\Order\Period;
use App\Models\Traits\Date;
use App\Models\Traits\Expeditor;
use Database\Factories\BrigadeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Brigade
 *
 * @property int $id
 * @property int $expeditor_id
 * @property string $date
 * @property string $period
 * @property int $car_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, User> $members
 * @property-read int|null $members_count
 *
 * @method static BrigadeFactory factory($count = null, $state = [])
 * @method static Builder|Brigade forDate(string $date)
 * @method static Builder|Brigade forExpeditor(int $expeditorId)
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder query()
 * @method static Builder|Brigade whereCarId($value)
 * @method static Builder|Brigade whereCreatedAt($value)
 * @method static Builder|Brigade whereDate($value)
 * @method static Builder|Brigade whereExternalId($value)
 * @method static Builder|Brigade whereId($value)
 * @method static Builder|Brigade wherePeriods($value)
 * @method static Builder|Brigade whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Brigade extends Model
{
    use Date;
    use Expeditor;
    use HasFactory;

    protected $fillable = [
        'expeditor_id',
        'date',
        'car_id',
        'period',
    ];

    protected $dispatchesEvents = [
        'saved' => WorkDayChanged::class,
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'brigade_members', 'brigade_id', 'expeditor_id');
    }

    public function hasMorningShift(): bool
    {
        return $this->period === Period::Morning->value;
    }

    public function hasEveningShift(): bool
    {
        return $this->period === Period::Evening->value;
    }
}

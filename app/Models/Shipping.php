<?php

namespace App\Models;

use App\Events\WorkDay\WorkDayChanged;
use App\Models\Traits\Date;
use App\Models\Traits\Expeditor;
use Database\Factories\ShippingFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Shipping
 *
 * @property int $id
 * @property string $date
 * @property int $expeditor_id
 * @property string $window_number
 * @property string $time_start
 * @property string $time_end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $expeditor
 *
 * @method static ShippingFactory factory($count = null, $state = [])
 * @method static Builder|Shipping forDate(string $date)
 * @method static Builder|Shipping forExpeditor(int $expeditorId)
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder query()
 * @method static Builder|Shipping whereCreatedAt($value)
 * @method static Builder|Shipping whereDate($value)
 * @method static Builder|Shipping whereExpeditorId($value)
 * @method static Builder|Shipping whereId($value)
 * @method static Builder|Shipping whereTimeEnd($value)
 * @method static Builder|Shipping whereTimeStart($value)
 * @method static Builder|Shipping whereUpdatedAt($value)
 * @method static Builder|Shipping whereWindowNumber($value)
 *
 * @mixin Eloquent
 */
class Shipping extends Model
{
    use Date;
    use Expeditor;
    use HasFactory;

    protected $fillable = [
        'date',
        'expeditor_id',
        'window_number',
        'time_start',
        'time_end',
    ];

    protected $dispatchesEvents = [
        'saved' => WorkDayChanged::class,
    ];

    public function expeditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expeditor_id', 'id');
    }
}

<?php

namespace App\Models;

use App\Events\Notification\NotificationCreated;
use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use function now;

/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $message
 * @property int $viewed
 * @property int $expeditor_id
 * @property int $order_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static NotificationFactory factory($count = null, $state = [])
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder query()
 * @method static Builder|Notification recentUnviewed(int $userId)
 * @method static Builder|Notification recentViewed(int $userId)
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereExpeditorId($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereMessage($value)
 * @method static Builder|Notification whereOrderId($value)
 * @method static Builder|Notification whereStatus($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @method static Builder|Notification whereViewed($value)
 *
 * @mixin Eloquent
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewed',
        'message',
        'expeditor_id',
        'status',
        'order_id',
    ];

    protected $dispatchesEvents = [
        'created' => NotificationCreated::class,
    ];

    public function scopeRecentViewed(Builder $query, int $userId): void
    {
        $query->where('expeditor_id', $userId)
            ->where('viewed', 1)
            ->where('created_at', '>', now()->subDays(1)); //достаем уведомления за сегодня
    }

    public function scopeRecentUnviewed(Builder $query, int $userId): void
    {
        $query->where('expeditor_id', $userId)
            ->where('viewed', 0)
            ->where('created_at', '>', now()->subDays(1));
    }
}

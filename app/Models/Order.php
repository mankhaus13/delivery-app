<?php

namespace App\Models;

use App\Models\Enums\Item\ItemType;
use App\Models\Enums\Order\OrderStatus;
use App\Models\Traits\Expeditor;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $external_id
 * @property int $expeditor_id
 * @property int $return_bottles
 * @property string $payment_method
 * @property string $status
 * @property string $period
 * @property string $client_name
 * @property string $address
 * @property string $address_extra_info
 * @property string $shipping_date
 * @property float $total
 * @property ?string $expected_delivery_time
 * @property string $order_comment
 * @property string $address_comment
 * @property int $empty_bottles
 * @property string $created_at
 * @property string $updated_at
 * @property ?string $wait_until
 * @property string $number
 *
 * @method static Builder|Order forDate(string $date) filter by shipping date
 * @method static Builder|Order active() filter by status active
 *
 * @property-read Collection<int, Item> $items
 * @property-read int|null $items_count
 *
 * @method static Builder|Order canceled()
 * @method static Builder|Order completed()
 * @method static Builder|Order activeOrToBeCanceled()
 * @method static OrderFactory factory($count = null, $state = [])
 * @method static Builder|User forExpeditor(int $expeditorId)
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder|Order pending()
 * @method static Builder query()
 * @method static Builder|Order whereAddress($value)
 * @method static Builder|Order whereAddressComment($value)
 * @method static Builder|Order whereAddressExtraInfo($value)
 * @method static Builder|Order whereClientName($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereEmptyBottles($value)
 * @method static Builder|Order whereExpectedDeliveryTime($value)
 * @method static Builder|Order whereExpeditorId($value)
 * @method static Order|Builder whereExternalId($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereOrderComment($value)
 * @method static Builder|Order wherePaymentMethod($value)
 * @method static Builder|Order wherePeriod($value)
 * @method static Builder|Order whereReturnBottles($value)
 * @method static Builder|Order whereShippingDate($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereTotal($value)
 * @method static Builder|Order whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Order extends Model
{
    use Expeditor;
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['graphic_bottles'];

    public static function scopeForDate(Builder $query, string $date): void
    {
        $query->where('shipping_date', $date);
    }

    public static function countCanceled(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->canceled()
            ->count();
    }

    public static function countCurrent(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->whereIn(
                'status',
                [
                    OrderStatus::Active->value,
                    OrderStatus::ToBeCanceled->value,
                    OrderStatus::Pending->value
                ]
            )
            ->count();
    }

    public static function countDelivered(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->completed()
            ->count();
    }

    public static function countAll(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->count();
    }

    public static function countEmptyBottles(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->sum('empty_bottles');
    }

    public static function countReturnBottles(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->sum('return_bottles');
    }

    public static function countTotal(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->sum('total');
    }

    public static function countActsOfDiscrepancy(string $date, int $expeditorId): int
    {
        return self::forDate($date)
            ->forExpeditor($expeditorId)
            ->completed()
            ->whereColumn('return_bottles', '!=', 'empty_bottles')
            ->count();
    }

    /**
     * Get the expeditor ID by order ID.
     *
     * @param  int  $orderId  The ID of the order.
     * @return int The expeditor ID
     */
    public static function getExpeditorIdByOrderId(int $orderId): int
    {
        return self::query()->where('id', $orderId)->value('expeditor_id');
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', OrderStatus::Completed->value);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', OrderStatus::Active->value);
    }

    public function scopeCanceled(Builder $query): void
    {
        $query->where('status', OrderStatus::Canceled->value);
    }

    public function scopeActiveOrToBeCanceled(Builder $query): void
    {
        $query->where('status', OrderStatus::Active->value)
            ->orWhere('status', OrderStatus::ToBeCanceled->value);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', OrderStatus::Pending->value);
    }

    public function getGraphicBottlesAttribute(): array
    {
        $selectQuery = '';
        $bindings = [];
        // Define a mapping between enum values and database column aliases dynamically
        $mapping = [];

        foreach (ItemType::values() as $enumValue) {
            $selectQuery .= 'SUM((CASE WHEN type = ? THEN 1 ELSE 0 END) * quantity) as ' . $enumValue . ', ';
            $bindings[] = $enumValue;
            $mapping[\strtolower($enumValue)] = $enumValue;
        }

        // Remove the trailing comma and space from the select query
        $selectQuery = \rtrim($selectQuery, ', ');

        $data = $this->items()->selectRaw($selectQuery, $bindings)->first()->toArray();

        // Map the keys in the resulting array using the defined mapping
        $graphicBottles = [];
        foreach ($mapping as $dataKey => $enumValue) {
            $graphicBottles[$enumValue] = $data[$dataKey];
        }

        return $graphicBottles;
    }

    public function getIntervalLabelAttribute(): string
    {
        $expectedDeliveryTimestamp = strtotime($this->expected_delivery_time);
        $lowerLimit = date('H:i', $expectedDeliveryTimestamp - 30 * 60);
        $higherLimit = date('H:i', $expectedDeliveryTimestamp + 30 * 60);
        return $lowerLimit . ' - ' . $higherLimit;
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'order_id', 'id');
    }

    public function expeditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expeditor_id', 'id');
    }

    public function discrepancyReason(): BelongsTo
    {
        return $this->belongsTo(BottlesDiscrepancyReason::class, 'discrepancy_reason_id', 'id');
    }

    public function cancelationReason(): BelongsTo
    {
        return $this->belongsTo(CancelationReason::class, 'cancel_reason_id', 'id');
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::Pending->value;
    }

    public function isActive(): bool
    {
        return $this->status === OrderStatus::Active->value;
    }

    public function isToBeCanceled(): bool
    {
        return $this->status === OrderStatus::ToBeCanceled->value;
    }

    public function setStatus(OrderStatus $status): bool
    {
        return $this->update(['status' => $status]);
    }

    public function hasDiscrepancy(int $emptyBottles): bool
    {
        return $this->return_bottles !== $emptyBottles;
    }
}

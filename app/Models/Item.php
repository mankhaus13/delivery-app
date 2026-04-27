<?php

namespace App\Models;

use Database\Factories\ItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Item
 *
 * @property int $id
 * @property string $image
 * @property string $name
 * @property int $quantity
 * @property string $price
 * @property int $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static ItemFactory factory($count = null, $state = [])
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder query()
 * @method static Builder|Item whereCreatedAt($value)
 * @method static Builder|Item whereId($value)
 * @method static Builder|Item whereImage($value)
 * @method static Builder|Item whereName($value)
 * @method static Builder|Item whereOrderId($value)
 * @method static Builder|Item wherePrice($value)
 * @method static Builder|Item whereQuantity($value)
 * @method static Builder|Item whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Item extends Model
{
    use HasFactory;

    protected $guarded = false;
}

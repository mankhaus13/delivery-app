<?php

namespace App\Models;

use App\Events\CancelationReason\CancelationReasonListChanged;
use Database\Factories\CancelationReasonFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\CancelationReason
 *
 * @property int $id
 * @property string $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static CancelationReasonFactory factory($count = null, $state = [])
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder query()
 * @method static Builder|CancelationReason whereCreatedAt($value)
 * @method static Builder|CancelationReason whereId($value)
 * @method static Builder|CancelationReason whereReason($value)
 * @method static Builder|CancelationReason whereUpdatedAt($value)
 *
 */
class CancelationReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
    ];

    protected $dispatchesEvents = [
        'created' => CancelationReasonListChanged::class,
        'updated' => CancelationReasonListChanged::class,
        'deleted' => CancelationReasonListChanged::class,
    ];
}

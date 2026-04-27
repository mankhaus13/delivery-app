<?php

namespace App\Models;

use Database\Factories\BrigadeMemberFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BrigadeMember
 *
 * @property int $id
 * @property string $fio
 * @property string $telephone
 * @property int $brigade_id
 * @property string $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static BrigadeMemberFactory factory($count = null, $state = [])
 * @method static Builder newModelQuery()
 * @method static Builder newQuery()
 * @method static Builder query()
 * @method static Builder|BrigadeMember whereBrigadeId($value)
 * @method static Builder|BrigadeMember whereCreatedAt($value)
 * @method static Builder|BrigadeMember whereExpeditorId($value)
 * @method static Builder|BrigadeMember whereId($value)
 * @method static Builder|BrigadeMember whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class BrigadeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'brigade_id',
        'position',
        'fio',
        'telephone',
    ];
}

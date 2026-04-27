<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\Models\User
 *
 * @property int $id
 * @property ?string $device_token
 * @property string $phone
 * @property string $password
 * @property string $first_name
 * @property string $second_name
 * @property string $surname
 * @property string $external_id
 * @property string $role
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Brigade> $brigades
 * @property-read int|null $brigades_count
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeviceToken($value)
 * @method static Builder|User whereExternalId($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereSecondName($value)
 * @method static Builder|User whereSurname($value)
 * @method static Builder|User whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'password',
        'first_name',
        'second_name',
        'surname',
        'device_token',
        'external_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getDeviceToken(): ?string
    {
        return $this->device_token;
    }

    public function isSubscribedForPushes(): bool
    {
        //пользователь может отказаться от рассылки пушей,
        return $this->device_token !== null;
    }

    public function brigades(): BelongsToMany
    {
        return $this->belongsToMany(
            Brigade::class,
            'brigade_members',
            'expeditor_id',
            'brigade_id'
        )->withPivot(
            'brigade_id',
            'expeditor_id',
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'expeditor_id', 'id');
    }

    public static function getIdByExternalId(string $externalId): int
    {
        return self::query()
            ->where('external_id', $externalId)
            ->value('id');
    }
}

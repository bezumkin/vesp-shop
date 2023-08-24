<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property ?string $receiver
 * @property ?string $email
 * @property ?string $phone
 * @property ?string $address
 * @property ?string $country
 * @property ?bool $gender
 * @property ?string $company
 * @property ?string $city
 * @property ?string $zip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Order[] $orders
 */
class UserAddress extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['full_address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([$this->receiver, $this->zip, $this->city, $this->address]));
    }
}
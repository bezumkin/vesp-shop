<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * @property int $id
 * @property int $user_id
 * @property ?string $receiver
 * @property ?string $email
 * @property ?string $phone
 * @property ?string $address
 * @property ?string $country
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

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([$this->receiver, $this->zip, $this->city, $this->address]));
    }

    public function fillData(array $data): UserAddress
    {
        $required = ['receiver', 'address', 'zip', 'city', 'phone', 'email'];
        foreach ($required as $key) {
            if (empty($data[$key])) {
                throw new RuntimeException('errors.address.no_' . $key);
            }
            if ($key === 'email' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('errors.address.wrong_email');
            }
        }
        $this->fill($data);

        return $this;
    }
}
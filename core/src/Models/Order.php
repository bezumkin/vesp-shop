<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $post
 * @property string $city
 * @property string $address
 * @property bool $paid
 * @property int $total
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $paid_at
 *
 * @property-read OrderProduct[] $orderProducts
 */
class Order extends Model
{
    protected $casts = ['paid' => 'boolean'];
    protected $guarded = ['id', 'created_at', 'updated_at', 'paid_at'];
    protected $dates = ['paid_at'];

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
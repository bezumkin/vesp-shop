<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $order_id
 * @property ?int $product_id
 * @property ?string $title
 * @property int $amount
 * @property float $price
 * @property float $weight
 * @property float $discount
 * @property array $options
 *
 * @property-read Order $order
 * @property-read Product $product
 */
class OrderProduct extends Model
{
    public $timestamps = false;
    protected $guarded = ['id', 'order_id'];
    protected $casts = [
        'price' => 'float',
        'weight' => 'float',
        'discount' => 'float',
        'options' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
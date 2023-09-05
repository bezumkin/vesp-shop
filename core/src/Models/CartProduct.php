<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property string $product_key
 * @property string $cart_id
 * @property int $product_id
 * @property int $amount
 * @property ?array $options
 *
 * @property-read Cart $cart
 * @property-read Product $product
 */
class CartProduct extends Model
{
    use Traits\CompositeKey;

    protected $primaryKey = ['cart_id', 'product_key'];
    protected $fillable = ['product_key', 'cart_id', 'product_id', 'amount', 'options'];
    protected $casts = [
        'options' => 'array',
    ];


    protected static function booted(): void
    {
        static::saving(static function (self $model) {
            if (!$model->product_key) {
                $model->product_key = self::generateKey($model->product, $model->options);
            }
        });
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function generateKey(Product $product, ?array $options = null): string
    {
        $key = (string)$product->id;
        if ($options) {
            $key .= json_encode($options);
        }

        return Uuid::uuid5(Uuid::NAMESPACE_OID, $key);
    }
}
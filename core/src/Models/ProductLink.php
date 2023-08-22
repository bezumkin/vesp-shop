<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $link_id
 * @property int $rank
 *
 * @property-read Product $product
 * @property-read Product $link
 */
class ProductLink extends Model
{
    use Traits\CompositeKey;
    use Traits\RankedModel;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['product_id', 'link_id'];
    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
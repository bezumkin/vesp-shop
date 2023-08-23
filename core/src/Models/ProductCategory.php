<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $category_id
 * @property int $rank
 *
 * @property-read Product $product
 * @property-read Category $category
 */
class ProductCategory extends Model
{
    use Traits\CompositeKey;
    use Traits\RankedModel;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['product_id', 'category_id'];
    protected $guarded = [];

    protected function getCurrentRank(): int
    {
        return $this->newQuery()->where('category_id', $this->category_id)->max('rank') + 1;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
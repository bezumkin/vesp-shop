<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property string $lang
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $content
 *
 * @property-read Product $product
 * @property-read Language $language
 */
class ProductTranslation extends Model
{
    use Traits\CompositeKey;

    public $timestamps = false;
    protected $primaryKey = ['product_id', 'lang'];
    protected $fillable = ['product_id', 'lang', 'title', 'subtitle', 'description', 'content'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang');
    }
}

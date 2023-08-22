<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property string $lang
 * @property string $title
 * @property bool $active
 * @property int $rank
 *
 * @property-read CategoryTranslation[] $categoriesTranslations
 * @property-read Category[] $categories
 * @property-read ProductTranslation[] $productsTranslations
 * @property-read Product[] $products
 */
class Language extends Model
{
    use Traits\RankedModel;

    public $timestamps = false;
    protected $primaryKey = 'lang';
    protected $keyType = 'string';
    protected $fillable = ['code', 'title', 'active', 'rank'];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function categoriesTranslations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class, 'lang');
    }

    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            Category::class,
            CategoryTranslation::class,
            'lang',
            'id',
            'lang',
            'category_id'
        );
    }

    public function productsTranslations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class, 'lang');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            ProductTranslation::class,
            'lang',
            'id',
            'lang',
            'product_id'
        );
    }
}

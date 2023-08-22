<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $category_id
 * @property string $alias
 * @property string $uri
 * @property float $price
 * @property float $old_price
 * @property string $article
 * @property float $weight
 * @property bool $new
 * @property bool $popular
 * @property bool $favorite
 * @property string $made_in
 * @property array $colors
 * @property array $variants
 * @property bool $active
 * @property int $rank
 * @property int $remote_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Category $category
 * @property-read ProductTranslation[] $translations
 * @property-read ProductCategory[] $productCategories
 * @property-read ProductFile[] $productFiles
 * @property-read ProductFile $firstFile
 * @property-read ProductLink[] $productLinks
 * @property-read ProductLink $productLink
 */
class Product extends Model
{
    use Traits\RankedModel;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'active' => 'boolean',
        'new' => 'boolean',
        'popular' => 'boolean',
        'favorite' => 'boolean',
        'colors' => 'array',
        'variants' => 'array',
        'discount_weight' => 'array',
        'discount_amount' => 'array',
        'price' => 'float',
        'old_price' => 'float',
        'weight' => 'float',
    ];
    protected $hidden = ['remote_id'];

    protected static function booted(): void
    {
        static::saving(static function (self $model) {
            $model->uri = implode('/', [$model->category->uri, $model->alias]);
        });
    }

    protected function getCurrentRank(): int
    {
        return $this->newQuery()->where('category_id', $this->category_id)->max('rank') + 1;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class);
    }

    public function firstFile(): HasOne
    {
        return $this
            ->hasOne(ProductFile::class)
            ->ofMany(['rank' => 'min'], function (Builder $c) {
                $c->where('active', true);
            })
            ->with('file:id,updated_at');
    }

    public function productLinks(): HasMany
    {
        return $this->hasMany(ProductLink::class, 'product_id');
    }

    public function productLink(): HasOne
    {
        return $this->hasOne(ProductLink::class, 'link_id');
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if (array_key_exists('first_file', $array)) {
            $array['file'] = !empty($array['first_file']) ? $array['first_file']['file'] : [];
            unset($array['first_file']);
        }

        return $array;
    }
}

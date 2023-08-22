<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $parent_id
 * @property ?int $file_id
 * @property string $alias
 * @property string $uri
 * @property bool $active
 * @property int $rank
 * @property int $remote_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Category $parent
 * @property-read CategoryTranslation[] $translations
 * @property-read File $file
 * @property-read Category[] $children
 * @property-read Product[] $products
 */
class Category extends Model
{
    use Traits\RankedModel;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = ['active' => 'boolean'];
    protected $hidden = ['remote_id'];
    public static bool $updateUri = false;

    protected static function booted(): void
    {
        static::saving(static function (self $model) {
            $uri = [$model->alias];
            if ($model->parent) {
                array_unshift($uri, $model->parent->uri);
            }
            $model->uri = implode('/', $uri);

            if (!self::$updateUri) {
                self::$updateUri = $model->exists && ($model->isDirty('parent_id') || $model->isDirty('alias'));
            }
        });


        static::saved(static function (self $model) {
            if (self::$updateUri) {
                /** @var Product $product */
                foreach ($model->products()->cursor() as $product) {
                    $product->save();
                }

                /** @var Category $category */
                foreach ($model->children()->cursor() as $category) {
                    $category::$updateUri = true;
                    $category->save();
                }
            }
        });
    }

    protected function getCurrentRank(): int
    {
        $c = $this->newQuery();
        if ($this->parent_id) {
            $c->where('parent_id', $this->parent_id);
        } else {
            $c->whereNull('parent_id');
        }

        return $c->max('rank') + 1;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
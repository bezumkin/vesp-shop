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
 * @property string $title
 * @property ?string $description
 * @property string $alias
 * @property string $sku
 * @property float $price
 * @property bool $active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Category $category
 * @property-read ProductFile[] $productFiles
 * @property-read ProductFile $firstFile
 */
class Product extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'active' => 'boolean',
        'price' => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $file_id
 * @property int $rank
 * @property bool $active
 *
 * @property-read Product $product
 * @property-read File $file
 */
class ProductFile extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['product_id', 'file_id'];
    protected $guarded = [];
    protected $casts = ['active' => 'bool'];

    public function getKey(): array
    {
        $key = [];
        foreach ($this->primaryKey as $item) {
            $key[$item] = $this->getAttribute($item);
        }

        return $key;
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        foreach ($this->getKey() as $key => $value) {
            $query->where($key, $this->original[$key] ?? $value);
        }

        return $query;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $file_id
 * @property bool $active
 * @property int $rank
 * @property int $remote_id
 *
 * @property-read Product $product
 * @property-read File $file
 */
class ProductFile extends Model
{
    use Traits\CompositeKey;
    use Traits\RankedModel;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['product_id', 'file_id'];
    protected $guarded = [];
    protected $casts = ['active' => 'bool'];
    protected $hidden = ['remote_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
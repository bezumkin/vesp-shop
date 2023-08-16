<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $category_id
 * @property string $lang
 * @property ?string $title
 * @property ?string $description
 *
 * @property-read Category $category
 * @property-read Language $language
 */
class CategoryTranslation extends Model
{
    use Traits\CompositeKey;
    public $timestamps = false;
    protected $primaryKey = ['category_id', 'lang'];
    protected $fillable = ['category_id', 'lang', 'title', 'description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang');
    }
}

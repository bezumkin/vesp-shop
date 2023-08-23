<?php

namespace App\Controllers\Admin\Product;

use App\Controllers\Traits\ProductPropertyController;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vesp\Controllers\ModelController;

class Categories extends ModelController
{
    use ProductPropertyController;

    protected $scope = 'products';
    protected $model = ProductCategory::class;
    protected $primaryKey = ['product_id', 'category_id'];

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('product_id', $this->product->id);

        if ($query = trim($this->getProperty('query', ''))) {
            $c->whereHas('category', static function (Builder $c) use ($query) {
                $c->whereHas('translations', static function (Builder $c) use ($query) {
                    $c->where('title', 'LIKE', "%$query%");
                    $c->orWhere('description', 'LIKE', "%$query%");
                });
            });
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('category', static function(BelongsTo $c) {
            $c->with('translations:category_id,lang,title');
            $c->with('file:id,updated_at');
        });

        return $c;
    }
}
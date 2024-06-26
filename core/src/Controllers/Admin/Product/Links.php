<?php

namespace App\Controllers\Admin\Product;

use App\Controllers\Traits\ProductPropertyController;
use App\Models\ProductLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vesp\Controllers\ModelController;

class Links extends ModelController
{
    use ProductPropertyController;

    protected string|array $scope = 'products';
    protected string $model = ProductLink::class;
    protected string|array $primaryKey = ['product_id', 'link_id'];

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('product_id', $this->product->id);

        if ($query = trim($this->getProperty('query', ''))) {
            $c->whereHas('link', static function (Builder $c) use ($query) {
                $c->whereHas('translations', static function (Builder $c) use ($query) {
                    $c->where('title', 'LIKE', "%$query%");
                    $c->orWhere('subtitle', 'LIKE', "%$query%");
                    $c->orWhere('description', 'LIKE', "%$query%");
                    $c->orWhere('content', 'LIKE', "%$query%");
                });
            });
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('link', function(BelongsTo $c) {
            $c->with('category:id,uri,active', 'category.translations:category_id,lang,title');
            $c->with('translations:product_id,lang,title');
            if (!$this->getProperty('combo')) {
                $c->with('firstFile');
            }
        });

        return $c;
    }
}
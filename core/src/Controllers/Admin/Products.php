<?php

namespace App\Controllers\Admin;

use App\Controllers\Traits\TranslateModelController;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Products extends ModelController
{
    use TranslateModelController;

    protected $scope = 'products';
    protected $model = Product::class;

    protected function beforeGet(Builder $c): Builder
    {
        return $c->with('translations');
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($category = (int)$this->getProperty('category')) {
            $c->where(static function (Builder $c) use ($category) {
                $c->where('category_id', $category);
                $c->orWhereHas('productCategories', static function (Builder $c) use ($category) {
                    $c->where('category_id', $category);
                });
            });
        }
        if ($query = trim($this->getProperty('query', ''))) {
            $c->whereHas('translations', static function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('subtitle', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
                $c->orWhere('content', 'LIKE', "%$query%");
            });
        }
        if ($exclude = $this->getProperty('exclude')) {
            if (!is_array($exclude)) {
                $exclude = explode(',', $exclude);
            }
            $c->whereNotIn('id', array_map('intval', $exclude));
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('category:id,uri,active', 'category.translations:category_id,lang,title');
        $c->with('translations:product_id,lang,title');
        if (!$this->getProperty('combo')) {
            $c->with('firstFile');
        }

        return $c;
    }

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Product $record */
        $c = Product::query()->where(['alias' => $record->alias, 'category_id' => $record->category_id]);
        if ($record->exists) {
            $c->where('id', '!=', $record->id);
        }
        if ($c->count()) {
            return $this->failure('errors.product.alias_exists');
        }

        return null;
    }
}
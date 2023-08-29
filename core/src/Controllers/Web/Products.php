<?php

namespace App\Controllers\Web;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vesp\Controllers\ModelGetController;

class Products extends ModelGetController
{
    protected $model = Product::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('active', true);
        $c->whereHas('category', static function (Builder $c) {
            $c->where('active', true);
        });

        $c->with('translations');
        $c->with('category:id,uri,active', 'category.translations:category_id,lang,title');
        $c->with('productFiles', static function (HasMany $c) {
            $c->where('active', true);
            $c->orderBy('rank');
            $c->select('product_id', 'file_id');
            $c->with('file:id,updated_at');
        });

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('active', true);
        $c->whereHas('category', static function (Builder $c) {
            $c->where('active', true);
        });

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('translations:product_id,lang,title');
        $c->with('category:id,uri,active', 'category.translations:category_id,lang,title');
        $c->with('firstFile');

        return $c;
    }

    public function prepareRow(Model $object): array
    {
        /** @var Product $object */
        $array = $object->toArray();
        if ($this->getPrimaryKey()) {
            $array['breadcrumbs'] = Category::getBreadcrumbs($object->category_id);
        }

        return $array;
    }

    protected function getPrimaryKey(): ?array
    {
        if ($uri = $this->getProperty('uri')) {
            return ['uri' => $uri];
        }

        return null;
    }
}
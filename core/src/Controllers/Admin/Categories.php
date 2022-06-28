<?php

namespace App\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Categories extends ModelController
{
    protected $scope = 'products';
    protected $model = Category::class;

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = $this->getProperty('query')) {
            $c->where(static function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
            });
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->withCount('products');

        return $c;
    }

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Category $record */
        $c = Category::query()->where('alias', $record->alias);
        if ($record->exists) {
            $c->where('id', '!=', $record->id);
        }
        if ($c->count()) {
            return $this->failure('errors.category.alias_exists');
        }

        return null;
    }
}
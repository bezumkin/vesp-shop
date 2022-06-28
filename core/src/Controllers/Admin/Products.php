<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Products extends ModelController
{
    protected $scope = 'products';
    protected $model = Product::class;

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
        $c->with('category:id,title');
        $c->with('firstFile');

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
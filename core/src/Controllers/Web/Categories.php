<?php

namespace App\Controllers\Web;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Categories extends ModelController
{
    protected $model = Category::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('active', true);

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        return $this->beforeGet($c);
    }

    protected function getPrimaryKey(): ?array
    {
        if ($alias = $this->getProperty('alias')) {
            return ['alias' => $alias];
        }

        return null;
    }
}
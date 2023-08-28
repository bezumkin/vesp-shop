<?php

namespace App\Controllers\Web;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Categories extends ModelGetController
{
    protected $model = Category::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('active', true);
        $c->with('translations');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        return $this->beforeGet($c);
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('translations:category_id,lang,title');

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        if ($uri = $this->getProperty('uri')) {
            return ['uri' => $uri];
        }

        return null;
    }
}
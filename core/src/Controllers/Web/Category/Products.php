<?php

namespace App\Controllers\Web\Category;

use App\Controllers\Traits\WebCategoryPropertyController;
use App\Models\Category;
use App\Services\CategoryFilter;
use Illuminate\Database\Eloquent\Builder;

class Products extends \App\Controllers\Web\Products
{
    use WebCategoryPropertyController;

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('active', true);
        if ($filters = $this->getProperty('filters')) {
            $service = new CategoryFilter($this->category);
            $ids = $service->getProducts(json_decode($filters, true));
            $c->whereIn('id', $ids);
        } else {
            $c->where(function (Builder $c) {
                $c->where('category_id', $this->category->id);
                if ($children = Category::getChildIds($this->category->id, true)) {
                    $c->orWhereIn('category_id', $children);
                }
                $c->orWhereHas('productCategories', function (Builder $c) {
                    $c->where('category_id', $this->category->id);
                });
            });
        }

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        return null;
    }
}
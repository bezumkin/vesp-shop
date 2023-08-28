<?php

namespace App\Controllers\Web\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Psr\Http\Message\ResponseInterface;

class Products extends \App\Controllers\Web\Products
{
    protected ?Category $category;

    public function checkScope(string $method): ?ResponseInterface
    {
        $c = Category::query()->where(['active' => true, 'id' => $this->getProperty('category_id')]);
        if (!$this->category = $c->first()) {
            return $this->failure('', 404);
        }

        return null;
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where(['active' => true, 'category_id' => $this->category->id]);

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        return null;
    }
}
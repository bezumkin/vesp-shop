<?php

namespace App\Controllers\Web\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Psr\Http\Message\ResponseInterface;

class Products extends \App\Controllers\Web\Products
{
    /** @var Category $category */
    protected $category;

    public function checkScope(string $method): ?ResponseInterface
    {
        $c = Category::query()->where(['active' => true, 'uri' => $this->getProperty('category')]);
        if (!$this->category = $c->first()) {
            return $this->failure('', 404);
        }

        return null;
    }

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('active', true);
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
        $c->where(['active' => true, 'category_id' => $this->category->id]);

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        if ($alias = $this->getProperty('alias')) {
            return ['alias' => $alias, 'category_id' => $this->category->id];
        }

        return null;
    }
}
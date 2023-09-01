<?php

namespace App\Controllers\Traits;

use App\Models\Category;
use Psr\Http\Message\ResponseInterface;

trait WebCategoryPropertyController
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
}
<?php

namespace App\Controllers\Admin;

use App\Controllers\Traits\FileModelController;
use App\Controllers\Traits\TranslateModelController;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Categories extends ModelController
{
    use FileModelController;
    use TranslateModelController;

    protected $scope = 'products';
    protected $model = Category::class;
    protected $attachments = ['file'];

    protected function beforeGet(Builder $c): Builder
    {
        $c->with('file:id,updated_at');
        $c->with('translations');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query', ''))) {
            $c->whereHas('translations', static function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
            });
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('translations:category_id,lang,title');
        $c->with('parent:id', 'parent.translations:category_id,lang,title');
        $c->with('file:id,updated_at');
        $c->withCount('children');
        $c->withCount('products');

        return $c;
    }

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Category $record */
        if (!$record->parent_id) {
            $record->parent_id = null;
        }
        if ($error = $this->processFiles($record)) {
            return $error;
        }

        $c = Category::query()->where('alias', $record->alias);
        if ($record->exists) {
            $c->where('id', '!=', $record->id);
        }
        if ($record->parent_id) {
            $c->where('parent_id', $record->parent_id);
        }
        if ($c->count()) {
            return $this->failure('errors.category.alias_exists');
        }

        return null;
    }
}
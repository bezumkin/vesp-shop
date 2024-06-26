<?php

namespace App\Controllers\Admin;

use App\Controllers\Traits\FileModelController;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Users extends ModelController
{
    use FileModelController;

    protected string|array $scope = 'users';
    protected string $model = User::class;
    public $attachments = ['file'];

    protected function beforeGet(Builder $c): Builder
    {
        $c->with('file:id,updated_at');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = $this->getProperty('query')) {
            $c->where(
                static function (Builder $c) use ($query) {
                    $c->where('username', 'LIKE', "%$query%");
                    $c->orWhere('fullname', 'LIKE', "%$query%");
                }
            );
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('role:id,title');
        $c->with('file:id,updated_at');

        return $c;
    }
}
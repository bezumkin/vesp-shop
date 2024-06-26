<?php

namespace App\Controllers\User;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Addresses extends ModelGetController
{
    protected string|array $scope = 'profile';
    protected string $model = UserAddress::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);

        return $c;
    }

    protected function addSorting(Builder $c): Builder
    {
        $c->orderByDesc('created_at');

        return $c;
    }
}
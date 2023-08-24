<?php

namespace App\Controllers\Admin\User;

use Illuminate\Database\Eloquent\Builder;

class Orders extends \App\Controllers\Admin\Orders
{
    protected function beforeGet(Builder $c): Builder
    {
        $c->where('user_id', $this->getProperty('user_id'));

        return parent::beforeGet($c);
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('user_id', $this->getProperty('user_id'));

        return parent::beforeCount($c);
    }
}
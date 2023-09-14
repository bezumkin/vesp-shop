<?php

namespace App\Controllers\User;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Orders extends ModelGetController
{
    protected $scope = 'profile';
    protected $model = Order::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);
        $c->with('address');
        $c->with('payment');
        $c->with('orderProducts', 'orderProducts.product');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where('num', 'LIKE', "$query%");
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->withCount('orderProducts');
        $c->with('payment:order_id,paid');

        return $c;
    }

    protected function addSorting(Builder $c): Builder
    {
        if ($sort = $this->getProperty('sort')) {
            if ($sort === 'num') {
                $dir = strtolower($this->getProperty('dir', '')) === 'desc' ? 'desc' : 'asc';
                $c->orderBy('created_at', $dir);
            } else {
                $c = parent::addSorting($c);
            }
        }

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        if ($uuid = $this->getProperty('uuid')) {
            return ['uuid' => $uuid];
        }

        return null;
    }
}
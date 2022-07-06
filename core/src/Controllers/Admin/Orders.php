<?php

namespace App\Controllers\Admin;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Orders extends ModelController
{
    protected $scope = 'orders';
    protected $model = Order::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->with('orderProducts');

        return $c;
    }

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Order $record */
        if ($record->paid && !$record->paid_at) {
            $record->paid_at = time();
        } elseif ($record->paid_at && !$record->paid) {
            $record->paid_at = null;
        }

        return null;
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = $this->getProperty('query')) {
            $c->where('title', 'LIKE', "%$query%");
        }

        return $c;
    }
}
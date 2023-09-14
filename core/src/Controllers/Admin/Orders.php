<?php

namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserAddress;
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
        $c->with('orderProducts', 'orderProducts.product');
        $c->with('address');
        $c->with('payment');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where(static function (Builder $c) use ($query) {
                $c->whereHas('user', static function (Builder $c) use ($query) {
                    $c->where('fullname', 'LIKE', "%$query%");
                    $c->orWhere('username', 'LIKE', "%$query%");
                });
                $c->orWhere('num', 'LIKE', "$query%");
            });
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->withCount('orderProducts');
        $c->with('user:id,fullname,username');
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

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Order $record */
        if (!$this->getProperty('address_id')) {
            $record->address_id = null;
        }

        return null;
    }

    protected function afterSave(Model $record): Model
    {
        /** @var Order $record */
        $items = $this->getProperty('order_products');
        if (is_array($items)) {
            $ids = [];
            foreach ($items as $item) {
                if (!$orderProduct = $record->orderProducts()->find($item['id'])) {
                    $orderProduct = new OrderProduct();
                    $orderProduct->order_id = $record->id;
                }
                if (empty($item['options'])) {
                    $item['options'] = null;
                }
                $orderProduct->fill($item);
                $orderProduct->save();
                $ids[] = $orderProduct->id;
            }
            $record->orderProducts()->whereNotIn('id', $ids)->delete();
        }

        if (!$record->address_id && $item = $this->getProperty('address')) {
            if (is_array($item) && !empty($item)) {
                /** @var UserAddress $address */
                $address = $record->user->addresses()->create($item);
                $record->address_id = $address->id;
            }
        }

        if ($record->payment && $payment = $this->getProperty('payment')) {
            $record->payment->paid = $payment['paid'] ?? null;
            $record->payment->save();
        }

        $record->calculate();

        return $record;
    }
}
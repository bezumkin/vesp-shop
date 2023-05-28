<?php

namespace App\Controllers\Web;

use App\Models\Order;
use App\Models\Product;
use App\Services\Mail;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Orders extends Controller
{
    public function put(): ResponseInterface
    {
        if (!$ordered = $this->getProperty('products')) {
            return $this->failure();
        }

        $orderTotal = 0;
        $orderProducts = [];
        foreach ($ordered as $id => $amount) {
            /** @var Product $product */
            if ($product = Product::query()->where('active', true)->find($id)) {
                $total = $product->price * $amount;
                $orderTotal += $total;
                $orderProducts[] = [
                    'product_id' => $id,
                    'title' => $product->title,
                    'amount' => $amount,
                    'price' => $product->price,
                    'total' => $total,
                ];
            }
        }

        $order = new Order();
        $order->fill($this->getProperties());
        $order->total = $orderTotal;
        if ($order->save()) {
            $order->orderProducts()->createMany($orderProducts);
        }
        // Use only if STMP is specified
        if ($order->email) {
            $error = (new Mail())->send(
                $order->email,
                'Спасибо за ваш заказ!',
                'email-order-new.tpl',
                ['data' => $order->toArray()]
            );
            if ($error) {
                return $this->failure($error);
            }
        }

        return $this->success();
    }
}
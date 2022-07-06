<?php

namespace App\Controllers\Web;

use App\Models\Order;
use App\Models\Product;
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

        return $this->success();
    }
}
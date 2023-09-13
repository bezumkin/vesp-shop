<?php

namespace App\Controllers\Web;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\UserAddress;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Orders extends Controller
{

    public function get(): ResponseInterface
    {
        $uuid = $this->getProperty('uuid');
        /** @var Order $order */
        if (!$uuid || !$order = Order::query()->where('uuid', $uuid)->first()) {
            return $this->failure('Not Found', 404);
        }

        if ($order->payment && $order->payment->paid === null) {
            $order->payment->checkStatus();
        }

        return $this->success($order->getData());
    }

    public function put(): ResponseInterface
    {
        $cartId = $this->getProperty('uuid');
        /** @var Cart $cart */
        if (!$cartId || !$cart = Cart::query()->find($cartId)) {
            return $this->failure('Not Found', 404);
        }

        $properties = $this->getProperties();
        if (!$user = $this->user) {
            try {
                $address = new UserAddress();
                $address->fillData($properties);

                $properties['fullname'] = $properties['receiver'] ?? null;
                $properties['username'] = $properties['email'] ?? null;
                $user = User::createUser($properties);
                $address->user_id = $user->id;
                $address->save();
            } catch (\Exception $e) {
                return $this->failure($e->getMessage());
            }
        } elseif (empty($properties['address_id']) || !$address = $user->addresses()->find($properties['address_id'])) {
            try {
                $address = new UserAddress(['user_id' => $user->id]);
                $address->fillData($properties);
                $address->save();
            } catch (\Exception $e) {
                return $this->failure($e->getMessage());
            }
        }

        $lang = $this->request->getHeaderLine('Content-Language') ?: 'ru';

        $order = new Order();
        $order->user_id = $user->id;
        $order->address_id = $address->id;
        $order->comment = $properties['comment'] ?? null;
        $order->weight = 0;
        $order->cart = 0;
        $order->discount = 0;

        $orderProducts = [];
        foreach ($cart->cartProducts()->orderBy('created_at')->cursor() as $cartProduct) {
            /** @var CartProduct $cartProduct */
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $cartProduct->product_id;
            $orderProduct->title = $cartProduct->product->getTitle($lang);
            $orderProduct->weight = $cartProduct->product->weight;
            $orderProduct->price = $cartProduct->product->price;
            $orderProduct->amount = $cartProduct->amount;
            $orderProduct->discount = 0;
            $orderProduct->options = $cartProduct->options;
            $orderProducts[] = $orderProduct;

            $order->weight += $cartProduct->product->weight * $cartProduct->amount;
            $order->cart += $cartProduct->product->price * $cartProduct->amount;
        }
        $order->total = $order->cart;

        $order->save();
        $order->orderProducts()->saveMany($orderProducts);

        $response = $order->toArray();
        if (!empty($properties['payment'])) {
            if ($payment = $order->createPayment($properties['payment'])) {
                if ($link = $payment->getLink()) {
                    $response['payment'] = $link;
                }
            }
        }
        $order->sendEmails($lang);

        return $this->success($response);
    }
}
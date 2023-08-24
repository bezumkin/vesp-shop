<?php

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\UserAddress;
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;
use Vesp\Services\Eloquent;

require __DIR__ . '/Products.php';

class Orders extends AbstractSeed
{
    public function getDependencies(): array
    {
        return ['Users', 'Categories', 'Products'];
    }

    public function run(): void
    {
        $db = (new Eloquent())->getDatabaseManager();
        $pdo = $db->getPdo();

        $users = [];
        /** @var User $user */
        foreach (User::query()->select('id', 'remote_id')->cursor() as $user) {
            $users[$user->remote_id] = $user->id;
        }

        $stmtOrder = $pdo->query('SELECT *  FROM `modx_ms2_orders` ORDER BY `id`');
        $stmtAddress = $pdo->prepare('SELECT *  FROM `modx_ms2_order_addresses` WHERE `id` = :id');
        while ($row = $stmtOrder->fetch(PDO::FETCH_ASSOC)) {
            if (!$order = Order::query()->find($row['id'])) {
                $order = new Order();
                $order->id = $row['id'];
                $order->uuid = Uuid::uuid4();
            }
            if (empty($users[$row['user_id']])) {
                continue;
            }
            $order->num = trim($row['num']);
            $order->user_id = $users[$row['user_id']];
            $order->total = (float)$row['cost'];
            $order->cart = (float)$row['cart_cost'];
            $order->weight = (float)$row['weight'];
            $order->comment = !empty($row['comment']) ? trim($row['comment']) : null;
            $order->created_at = $row['createdon'];
            $order->updated_at = $row['updatedon'];
            if (!empty($row['properties']) && $properties = json_decode($row['properties'], true)) {
                $order->discount = isset($properties['extfld_discount']) ? (float)$properties['extfld_discount'] : 0;
            }

            if ($row['address'] && !$order->address_id && $stmtAddress->execute([':id' => $row['address']])) {
                if (!$old = $stmtAddress->fetch(PDO::FETCH_ASSOC)) {
                    throw new RuntimeException('Could not load address with id = ' . $row['address']);
                }
                if (!$address = UserAddress::query()->find($old['id'])) {
                    $address = new UserAddress();
                    $address->id = $old['id'];
                }
                $address->user_id = $users[$old['user_id']];
                $address->receiver = $old['receiver'] ? trim($old['receiver']) : null;
                $address->phone = $old['phone'] ? trim($old['phone']) : null;
                $address->address = trim(
                    implode(', ', array_filter([$old['metro'], $old['street'], $old['building'], $old['room']]))
                );
                $address->country = $old['country'] ? trim($old['country']) : null;
                $address->city = $old['city'] ? trim($old['city']) : null;
                $address->zip = $old['index'] ? trim($old['index']) : null;
                $address->created_at = $old['createdon'];
                $address->updated_at = $old['updatedon'];
                $address->save();

                $order->address_id = $address->id;
            }

            $order->save();
            $orders[] = $order->id;
        }

        $orders = Order::query()->pluck('id')->toArray();
        $stmt = $pdo->query("SELECT *  FROM `modx_ms2_order_products` ORDER BY `id`");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!in_array($row['order_id'], $orders, true)) {
                continue;
            }
            if (!$orderProduct = OrderProduct::query()->find($row['id'])) {
                $orderProduct = new OrderProduct();
                $orderProduct->id = $row['id'];
            }
            $orderProduct->order_id = $row['order_id'];
            $orderProduct->title = !empty($row['name']) ? trim($row['name']) : null;
            $orderProduct->amount = (int)$row['count'];
            $orderProduct->price = (float)$row['price'];
            $orderProduct->weight = (float)$row['weight'];
            /** @var Product $product */
            if ($product = Product::query()->where('remote_id', $row['product_id'])->first()) {
                $orderProduct->product_id = $product->id;
                /** @var ProductTranslation $translation */
                if (!$orderProduct->title && $translation = $product->translations()->where('lang', 'ru')->first()) {
                    $orderProduct->title = $translation->title;
                }
            }
            if (!empty($row['options']) && $options = json_decode($row['options'], true)) {
                if (isset($options['color'])) {
                    $options['colors'] = $options['color'];
                    unset($options['color']);
                }
                if (isset($options['variants_fr'])) {
                    $options['variants'] = Products::translateVariants($options['variants_fr'], 'fr');
                    unset($options['variants_fr']);
                }
                if (isset($options['discount'])) {
                    if (preg_match('#(\d+)%#', $options['discount'], $matches)) {
                        $tmp = $matches[1] / 100;
                        $orderProduct->discount = round($orderProduct->price * $tmp, 2);
                    }
                    unset($options['discount']);
                }
                $orderProduct->options = $options ?: null;
            }
            $orderProduct->save();
        }
    }
}
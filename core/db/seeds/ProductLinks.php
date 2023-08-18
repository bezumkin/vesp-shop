<?php

use App\Models\Product;
use App\Models\ProductLink;
use Phinx\Seed\AbstractSeed;
use Vesp\Services\Eloquent;

class ProductLinks extends AbstractSeed
{
    public function getDependencies(): array
    {
        return ['Products'];
    }

    public function run(): void
    {
        $pdo = (new Eloquent())->getDatabaseManager()->getPdo();
        $stmt = $pdo->query("SELECT * FROM `modx_ms2_product_links`");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @var Product $product */
            $product = Product::query()->where('remote_id', $row['master'])->first();
            /** @var Product $link */
            $link = Product::query()->where('remote_id', $row['slave'])->first();
            if ($product && $link) {
                ProductLink::query()->updateOrInsert(['product_id' => $product->id, 'link_id' => $link->id]);
            }
        }
    }
}
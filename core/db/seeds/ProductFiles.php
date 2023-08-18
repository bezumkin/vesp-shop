<?php

use App\Models\File;
use App\Models\Product;
use Phinx\Seed\AbstractSeed;
use Slim\Psr7\UploadedFile;
use Vesp\Services\Eloquent;

class ProductFiles extends AbstractSeed
{

    public function getDependencies(): array
    {
        return ['Categories', 'Products'];
    }

    public function run(): void
    {
        $pdo = (new Eloquent())->getDatabaseManager()->getPdo();
        $stmt = $pdo->prepare(
            'SELECT * FROM `modx_ms2_product_files` WHERE `product_id` = :remote_id AND `parent` = 0'
        );

        /** @var Product $product */
        foreach (Product::query()->cursor() as $product) {
            $stmt->execute([':remote_id' => $product->remote_id]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Check if File already uploaded
                if ($product->productFiles()->where('remote_id', $row['id'])->count()) {
                    continue;
                }

                // Upload new
                $filename = tempnam(getenv('UPLOAD_DIR'), 'img_');
                file_put_contents($filename, file_get_contents('https://i.pravatar.cc/500'));
                $data = new UploadedFile($filename, $row['name'] ?? basename($filename), mime_content_type($filename));
                $file = new File();
                $file->uploadFile($data);
                $product->productFiles()->insert([
                    'product_id' => $product->id,
                    'file_id' => $file->id,
                    'rank' => $row['rank'],
                    'remote_id' => $row['id'],
                ]);
                unlink($filename);
            }
        }
    }
}
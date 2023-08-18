<?php

use App\Models\Category;
use App\Models\Product;
use Phinx\Seed\AbstractSeed;
use Vesp\Services\Eloquent;

class Products extends AbstractSeed
{

    public function getDependencies(): array
    {
        return ['Languages', 'Categories'];
    }

    public function run(): void
    {
        $categories = [];
        /** @var Category $category */
        foreach (Category::query()->select('id', 'remote_id')->cursor() as $category) {
            $categories[$category->remote_id] = $category->id;
        }

        $pdo = (new Eloquent())->getDatabaseManager()->getPdo();
        $stmt = $pdo->query(
            "SELECT `r`.*, `d`.*, GROUP_CONCAT(`c`.`category_id` SEPARATOR ',') AS `categories`, 
            `ln`.`pagetitle` as `pagetitle_fr`, `ln`.`longtitle` as `longtitle_fr`, `ln`.`description` as `description_fr`, `ln`.`content` as `content_fr`
            FROM `modx_site_content` `r`
            LEFT JOIN `modx_ms2_products` `d` ON `r`.`id` = `d`.`id`
            LEFT JOIN `modx_ms2_product_categories` `c` ON `r`.`id` = `c`.`product_id`
            LEFT JOIN `modx_lingua_site_content` `ln` ON `ln`.`resource_id` = `r`.`id`
            WHERE `class_key` = 'msProduct'
            GROUP BY `r`.`id`
            ORDER BY `r`.`parent`,`r`.`id` ASC"
        );
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!$product = Product::query()->where('remote_id', $row['id'])->first()) {
                $product = new Product();
                $product->remote_id = $row['id'];
            }
            if (empty($categories[$row['parent']])) {
                throw new RuntimeException('Could not find product primary category ' . print_r($row, true));
            }
            $product->category_id = $categories[$row['parent']];
            $product->alias = trim($row['alias']);
            $product->uri = preg_replace('#^shop/#', '', trim($row['uri'], '/'));

            $product->price = $row['price'] ?? 0;
            $product->old_price = $row['old_price'] ?? 0;
            $product->article = $row['article'] ?? null;
            $product->weight = $row['weight'] ?? 0;

            $product->new = !empty($row['new']);
            $product->popular = !empty($row['popular']);
            $product->favorite = !empty($row['favorite']);

            $product->made_in = !empty($row['made_in']) ? trim($row['made_in']) : null;
            $product->colors = $row['color'] && $row['color'] !== '[""]' ? json_decode($row['color'], true) : null;

            // Variants
            if ($de = $row['variants'] && $row['variants'] !== '[""]' ? json_decode($row['variants'], true) : null) {
                $de = array_filter($de);
            }
            if (!$de && $fr = $row['variants_fr'] && $row['variants_fr'] !== '[""]' ? json_decode($row['variants_fr'], true) : null) {
                if ($fr = array_filter($fr)) {
                    $de = [];
                    foreach ($fr as $tmp) {
                        $de[] = self::translateVariants($tmp, 'fr');
                    }
                }
            }
            if ($de) {
                $product->variants = $de;
            }

            $product->active = empty($row['deleted']) && !empty($row['published']);
            $product->rank = $row['menuindex'];
            $product->created_at = $row['createdon'];
            $product->updated_at = $row['editedon'] ?: null;
            $product->save();

            $product->productCategories()->delete();
            if (!empty($row['categories'])) {
                $tmp = explode(',', $row['categories']);
                foreach ($tmp as $id) {
                    if (isset($categories[$id]) && (int)$categories[$id] !== $product->category_id) {
                        $product->productCategories()->insert(
                            ['product_id' => $product->id, 'category_id' => $categories[$id]]
                        );
                    }
                }
            }

            $product->translations()->updateOrCreate(['lang' => 'ru'], [
                'title' => trim($row['pagetitle']) ?: null,
                'subtitle' => trim($row['longtitle']) ?: null,
                'description' => trim($row['description']) ?: null,
                'content' => trim($row['content']) ?: null,
            ]);
            $product->translations()->updateOrCreate(['lang' => 'en'], [
                'title' => trim($row['pagetitle_fr']) ?: null,
                'subtitle' => trim($row['longtitle_fr']) ?: null,
                'description' => trim($row['description_fr']) ?: null,
                'content' => trim($row['content_fr']) ?: null,
            ]);
        }
    }

    public static function translateVariants(string $key, string $fromLang = 'de'): string
    {
        $de = [
            'Reinschnitt',
            'Reinschnitt (1.5mm)',
            'Mittelschnitt',
            'Mittelschnitt (3.5mm)',
            'Grobschnitt',
            'Grobschnitt (9mm)',
            'Mittel-Grob',
            'apfel',
            'aprikose',
            'banane',
            'kirsche',
            'mango',
            'mojito',
            'pfirsich',
            'gerade',
            'gebogen',
            'gerade',
            'Quadrate',
            'frankreich',
            'schwarz',
            'Deutschland',
            'Spanien',
            'Italien',
            'Kuh',
            'Lines',
        ];
        $fr = [
            'Moins fine',
            'Moins fine (1.5mm)',
            'Moyenne',
            'Moyenne (3.5mm)',
            'Grossière',
            'Grossière (9mm)',
            'Moyenne-Grossiè',
            'pomme',
            'apricot',
            'banane',
            'cherry',
            'mangue',
            'mojito',
            'peach',
            'droite',
            'courbee',
            'droit',
            'Carré',
            'france',
            'black',
            'allemagne',
            'espagne',
            'italie',
            'Vache',
            'Lines',
        ];

        if ($key === 'courbée') {
            $key = 'courbee';
        }

        $tmp = array_map('strtolower', $fromLang === 'de' ? $de : $fr);
        $idx = array_search(strtolower($key), $tmp, true);
        if ($idx === false) {
            return $key;
        }

        return $fromLang === 'de' ? $fr[$idx] : $de[$idx];
    }
}
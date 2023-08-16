<?php

use App\Models\Category;
use App\Models\File;
use Phinx\Seed\AbstractSeed;
use Slim\Psr7\UploadedFile;
use Vesp\Services\Eloquent;

class Categories extends AbstractSeed
{
    public function getDependencies(): array
    {
        return ['Languages'];
    }

    public function run(): void
    {
        $tvImageId = 1;

        $pdo = (new Eloquent())->getDatabaseManager()->getPdo();
        $stmt = $pdo->query(
            "SELECT `r`.*, `tv`.`value` AS `image`, `ln`.`pagetitle` as `pagetitle_fr`, `ln`.`content` as `content_fr`
            FROM `modx_site_content` `r`
            LEFT JOIN `modx_site_tmplvar_contentvalues` `tv` ON `tmplvarid` = '$tvImageId' AND `contentid` = `r`.`id`
            LEFT JOIN `modx_lingua_site_content` `ln` ON `resource_id` = `r`.`id`
            WHERE `class_key` = 'msCategory' ORDER BY `parent`,`r`.`id`"
        );
        $parents = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!$category = Category::query()->where('remote_id', $row['id'])->first()) {
                $category = new Category();
                $category->remote_id = $row['id'];
            }
            $category->alias = trim($row['alias']);
            $category->uri = preg_replace('#^shop/#', '', trim($row['uri'], '/'));
            $category->active = empty($row['deleted']) && !empty($row['published']);
            $category->rank = $row['menuindex'];
            $category->created_at = $row['createdon'];
            $category->updated_at = $row['editedon'] ?: null;
            $category->parent_id = $parents[$row['parent']] ?? null;

            // Process TV with image
            if (!empty($row['image'])) {
                $filename = tempnam(getenv('UPLOAD_DIR'), 'img_');
                file_put_contents($filename, file_get_contents('https://i.pravatar.cc/500'));
                $data = new UploadedFile($filename, basename($filename), mime_content_type($filename));
                if (!$file = $category->file) {
                    $file = new File();
                }
                $file->uploadFile($data);
                $category->update(['file_id' => $file->id]);
                unlink($filename);
            }
            // --

            $category->save();
            $category->translations()->updateOrCreate(['lang' => 'ru'], [
                'title' => trim($row['pagetitle']) ?: null,
                'description' => trim($row['content']) ?: null,
            ]);
            $category->translations()->updateOrCreate(['lang' => 'en'], [
                'title' => trim($row['pagetitle_fr']) ?: null,
                'description' => trim($row['content_fr']) ?: null,
            ]);

            $parents[$category->remote_id] = $category->id;
        }
    }
}
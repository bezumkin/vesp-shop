<?php

use App\Models\Category;
use App\Models\Product;
use Phinx\Seed\AbstractSeed;
use Vesp\Services\Eloquent;

class Products extends AbstractSeed
{
    protected $categories = 100;
    protected $products = 1000;

    public function run(): void
    {
        if (!class_exists('Faker\Factory')) {
            return;
        }

        (new Eloquent())->getConnection()->getSchemaBuilder()->disableForeignKeyConstraints();
        Category::query()->truncate();
        Product::query()->truncate();

        $faker = Faker\Factory::create();

        for ($i = 0; $i < $this->categories; $i++) {
            Category::query()->create([
                'title' => 'Категория ' . ($i + 1),
                'description' => $faker->text,
                'active' => random_int(0, 9) > 0,
            ]);
        }

        for ($i = 0; $i < $this->products; $i++) {
            Product::query()->create([
                'title' => 'Товар ' . ($i + 1),
                'description' => $faker->text,
                'category_id' => random_int(1, $this->categories),
                'sku' => $faker->unique()->randomNumber(9, true),
                'price' => $faker->randomFloat(2, 100, 10000),
                'active' => random_int(0, 9) > 0,
            ]);
        }
    }
}

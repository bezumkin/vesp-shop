<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class ProductCategories extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'product_categories',
            static function (Blueprint $table) {
                $table->foreignId('product_id')
                    ->constrained('products')->cascadeOnDelete();
                $table->foreignId('category_id')
                    ->constrained('categories')->cascadeOnDelete();
                $table->unsignedInteger('rank')->default(0);

                $table->primary(['product_id', 'category_id']);
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('product_categories');
    }
}

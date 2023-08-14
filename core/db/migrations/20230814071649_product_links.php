<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class ProductLinks extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'product_links',
            static function (Blueprint $table) {
                $table->foreignId('product_id')
                    ->constrained('products')->cascadeOnDelete();
                $table->foreignId('link_id')
                    ->constrained('products')->cascadeOnDelete();
                $table->foreignId('rank')->default(0)->index();

                $table->primary(['product_id', 'link_id']);
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('product_links');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Carts extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'carts',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignId('user_id')->nullable()
                    ->constrained('users')->nullOnDelete();
                $table->timestamps();
            }
        );

        $this->schema->create(
            'cart_products',
            function (Blueprint $table) {
                $table->foreignUuid('cart_id')
                    ->constrained('carts')->cascadeOnDelete();
                $table->foreignId('product_id')
                    ->constrained('products')->cascadeOnDelete();
                $table->uuid('product_key');
                $table->unsignedInteger('amount')->default(1);
                $table->json('options')->nullable();
                $table->timestamps();

                $table->primary(['cart_id', 'product_key']);
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('cart_products');
        $this->schema->drop('carts');
    }
}

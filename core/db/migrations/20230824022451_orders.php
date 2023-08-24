<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Orders extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'orders',
            static function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->string('num')->index();
                $table->foreignId('user_id')
                    ->constrained('users')->cascadeOnDelete();
                $table->decimal('total')->default(0);
                $table->decimal('cart')->default(0);
                $table->decimal('discount')->default(0);
                $table->decimal('weight')->default(0);
                $table->text('comment')->nullable();
                $table->timestamps();
            }
        );

        $this->schema->create(
            'order_products',
            static function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')
                    ->constrained('orders')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()
                    ->constrained('products')->nullOnDelete();
                $table->string('title')->nullable();
                $table->unsignedInteger('amount')->default(0);
                $table->decimal('price')->default(0);
                $table->decimal('weight')->default(0);
                $table->decimal('discount')->default(0);
                $table->json('options')->nullable();
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('order_products');
        $this->schema->drop('orders');
    }
}

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
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->char('post', 6)->nullable();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->boolean('paid')->default(false);
                $table->unsignedDecimal('total')->nullable();
                $table->timestamps();
                $table->timestamp('paid_at')->nullable();
            }
        );

        $this->schema->create(
            'order_products',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')
                    ->constrained('orders')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()
                    ->constrained('products')->nullOnDelete();
                $table->string('title');
                $table->unsignedDecimal('price');
                $table->unsignedInteger('amount')->default(1);
                $table->unsignedDecimal('total');
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('order_products');
        $this->schema->drop('orders');
    }
}

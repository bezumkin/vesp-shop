<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Products extends Migration
{

    public function up(): void
    {
        $this->schema->create(
            'categories',
            function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->boolean('active')->default(true)->index();
                $table->timestamps();
            }
        );

        $this->schema->create(
            'products',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')
                    ->constrained('categories')->restrictOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('sku')->unique();
                $table->unsignedDecimal('price')->nullable();
                $table->boolean('active')->default(true)->index();
                $table->timestamps();
            }
        );
    }


    public function down(): void
    {
        $this->schema->drop('products');
        $this->schema->drop('categories');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Products extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'products',
            static function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')
                    ->constrained('categories')->cascadeOnDelete();
                $table->string('alias');
                $table->string('uri')->unique();

                $table->decimal('price')->default(0);
                $table->decimal('old_price')->default(0);
                $table->string('article')->nullable();
                $table->decimal('weight')->nullable();

                $table->boolean('new')->default(false)->index();
                $table->boolean('popular')->default(false)->index();
                $table->boolean('favorite')->default(false)->index();

                $table->string('made_in')->nullable();
                $table->json('colors')->nullable();
                $table->json('variants')->nullable();

                $table->boolean('active')->default(true);
                $table->unsignedInteger('rank')->default(0);
                $table->unsignedInteger('remote_id')->nullable()->index();
                $table->timestamps();

                $table->index(['uri', 'active']);
                $table->index(['category_id', 'rank']);
            }
        );

        $this->schema->create(
            'product_translations',
            static function (Blueprint $table) {
                $table->foreignId('product_id')
                    ->constrained('products')->cascadeOnDelete();
                $table->char('lang', 2);

                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->text('description')->nullable();
                $table->text('content')->nullable();

                $table->primary(['product_id', 'lang']);
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('product_translations');
        $this->schema->drop('products');
    }
}

<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Eloquent;
use Vesp\Services\Migration;

final class Aliases extends Migration
{
    public function up(): void
    {
        (new Eloquent())->getConnection()->getSchemaBuilder()->disableForeignKeyConstraints();
        Category::query()->truncate();
        Product::query()->truncate();

        $this->schema->table(
            'categories',
            function (Blueprint $table) {
                $table->string('alias')->after('description')->unique();
            }
        );

        $this->schema->table(
            'products',
            function (Blueprint $table) {
                $table->string('alias')->after('description');
                $table->unique(['category_id', 'alias']);
            }
        );
    }

    public function down(): void
    {
        $this->schema->table(
            'products',
            function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropUnique(['category_id', 'alias']);
                $table->dropColumn('alias');
                $table->foreign('category_id')->references('id')
                    ->on('categories')->cascadeOnDelete();
            }
        );

        $this->schema->table(
            'categories',
            function (Blueprint $table) {
                $table->dropColumn('alias');
            }
        );
    }
}

<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class ProductFiles extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'product_files',
            function (Blueprint $table) {
                $table->foreignId('product_id')
                    ->constrained('products')->cascadeOnDelete();
                $table->foreignId('file_id')
                    ->constrained('files')->cascadeOnDelete();
                $table->unsignedInteger('rank')->default(0);
                $table->boolean('active')->default(true)->index();

                $table->primary(['product_id', 'file_id']);
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('product_files');
    }
}

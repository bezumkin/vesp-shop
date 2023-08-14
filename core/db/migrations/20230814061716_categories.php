<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Categories extends Migration
{
public function up(): void
{
    $this->schema->create(
        'categories',
        static function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()
                ->constrained('files')->nullOnDelete();
            $table->string('alias');
            $table->string('uri')->unique();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('rank')->default(0);
            $table->unsignedInteger('remote_id')->nullable()->index();
            $table->timestamps();

            $table->index(['uri', 'active']);
        }
    );

    $this->schema->table(
        'categories',
        static function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')
                ->constrained('categories')->nullOnDelete();

            $table->index(['parent_id', 'rank']);
        }
    );

    $this->schema->create(
        'category_translations',
        static function (Blueprint $table) {
            $table->foreignId('category_id')
                ->constrained('categories')->cascadeOnDelete();
            $table->char('lang', 2);

            $table->string('title');
            $table->text('description')->nullable();

            $table->primary(['category_id', 'lang']);
            $table->unique(['lang', 'title']);
            $table->foreign('lang')
                ->references('lang')
                ->on('languages')
                ->cascadeOnDelete();
        }
    );
}

    public function down(): void
    {
        $this->schema->drop('category_translations');
        $this->schema->drop('categories');
    }
}

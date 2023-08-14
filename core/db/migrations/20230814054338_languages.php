<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Languages extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'languages',
            function (Blueprint $table) {
                $table->char('lang', 2)->primary();
                $table->string('title')->unique();
                $table->unsignedInteger('rank')->nullable()->index();
                $table->boolean('active')->default(true)->index();
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('languages');
    }
}

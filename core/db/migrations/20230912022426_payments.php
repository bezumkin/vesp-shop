<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class Payments extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'payments',
            static function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')
                    ->constrained('orders')->cascadeOnDelete();
                $table->foreignId('user_id')
                    ->constrained('users')->cascadeOnDelete();
                $table->string('service');
                $table->decimal('amount');
                $table->boolean('paid')->nullable()->index();
                $table->string('link')->nullable();
                $table->string('remote_id')->nullable()->index();
                $table->timestamps();
                $table->timestamp('paid_at')->nullable();
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('payments');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

final class UserAddresses extends Migration
{
    public function up(): void
    {
        $this->schema->create(
            'user_addresses',
            static function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                    ->constrained('users')->cascadeOnDelete();
                $table->string('receiver')->nullable();
                $table->string('phone')->nullable();
                $table->tinyInteger('gender')->nullable();
                $table->string('company')->nullable();
                $table->string('address')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('zip')->nullable();
                $table->timestamps();
            }
        );

        $this->schema->table(
            'orders',
            static function (Blueprint $table) {
                $table->foreignId('address_id')->after('user_id')->nullable()
                    ->constrained('user_addresses')->nullOnDelete();
            }
        );
    }

    public function down(): void
    {
        $this->schema->table(
            'orders',
            static function (Blueprint $table) {
                $table->dropConstrainedForeignId('address_id');
            }
        );
        $this->schema->drop('user_addresses');
    }
}

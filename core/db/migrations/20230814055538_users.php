<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class Users extends Migration
{

    public function up(): void
    {
        $this->schema->create(
            'user_roles',
            function (Blueprint $table) {
                $table->id();
                $table->string('title')->unique();
                $table->json('scope')->nullable();
                $table->timestamps();
            }
        );

        $this->schema->create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->string('fullname')->nullable();
                $table->string('password');
                $table->string('tmp_password')->nullable();
                $table->string('salt')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();

                $table->tinyInteger('gender')->nullable();
                $table->string('company')->nullable();
                $table->string('address')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('zip')->nullable();
                $table->char('lang', 2)->nullable();

                $table->foreignId('role_id')
                    ->constrained('user_roles')->cascadeOnDelete();
                $table->foreignId('file_id')->nullable()
                    ->constrained('files')->nullOnDelete();
                $table->boolean('active')->default(true);
                $table->boolean('blocked')->default(false);
                $table->text('comment')->nullable();
                $table->unsignedInteger('remote_id')->nullable()->index();
                $table->timestamps();

                $table->index(['active', 'blocked']);
                $table->foreign('lang')
                    ->references('lang')
                    ->on('languages')
                    ->nullOnDelete();
            }
        );

        $this->schema->create(
            'user_tokens',
            function (Blueprint $table) {
                $table->string('token')->primary();
                $table->foreignId('user_id')
                    ->constrained('users')->cascadeOnDelete();
                $table->timestamp('valid_till')->index();
                $table->string('ip', 16)->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();

                $table->index(['token', 'user_id', 'active']);
            }
        );
    }


    public function down(): void
    {
        $this->schema->drop('user_tokens');
        $this->schema->drop('users');
        $this->schema->drop('user_roles');
    }
}

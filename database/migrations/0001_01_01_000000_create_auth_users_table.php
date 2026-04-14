<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('photo_id')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete('email');
            
            $table->index(['email', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_users');
    }
};

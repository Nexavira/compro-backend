<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('auth_detail_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('photo_id')->nullable();
            $table->foreignId('user_id')->constrained('auth_users')->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone_number');

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->index(['user_id', 'full_name', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_detail_users');
    }
};

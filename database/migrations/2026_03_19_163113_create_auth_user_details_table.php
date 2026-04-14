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
        Schema::create('auth_user_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_user_details');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('auth_role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('auth_roles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('auth_users')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_role_user');
    }
};

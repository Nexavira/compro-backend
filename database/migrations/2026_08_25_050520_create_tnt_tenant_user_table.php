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
        Schema::create('tnt_tenant_user', function (Blueprint $table) {
            $table->foreignId('tenant_id')->constrained('tnt_tenants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('auth_users')->onDelete('cascade');
            $table->string('role_detail')->nullable();

            $table->primary(['user_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tnt_tenant_user');
    }
};

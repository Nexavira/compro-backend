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
        Schema::create('sys_api_keys', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tnt_tenants')->onDelete('cascade');
            
            $table->string('name');
            $table->string('token')->unique();
            
            $table->timestamp('last_used_at')->nullable(); 

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->index(['tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_api_keys');
    }
};

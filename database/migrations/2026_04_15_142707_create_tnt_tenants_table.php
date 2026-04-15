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
        Schema::create('tnt_tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('logo_id')->constrained('sys_files')->onDelete('cascade');
            $table->foreignId('favicon_id')->constrained('sys_files')->onDelete('cascade');
            $table->foreignId('tenant_category_id')->constrained('tnt_tenant_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('theme_code');
            $table->string('custom_domain')->nullable();
            $table->json('settings')->nullable();
            $table->integer('is_suspended')->default(0);
            $table->text('description')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['name', 'code']);

            $table->index(['name', 'code', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tnt_tenants');
    }
};

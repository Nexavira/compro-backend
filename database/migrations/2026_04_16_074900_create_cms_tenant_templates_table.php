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
        Schema::create('cms_tenant_templates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tnt_tenants')->onDelete('cascade');
            $table->foreignId('global_template_id')->nullable()->constrained('cms_global_templates')->onDelete('cascade');
            $table->jsonb('template_settings')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['tenant_id', 'global_template_id']);

            $table->index(['tenant_id', 'global_template_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_tenant_templates');
    }
};

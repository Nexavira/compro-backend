<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_template_id')->constrained('cms_tenant_templates')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->jsonb('template_data')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['tenant_template_id', 'slug']);

            $table->index(['tenant_template_id', 'slug', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};

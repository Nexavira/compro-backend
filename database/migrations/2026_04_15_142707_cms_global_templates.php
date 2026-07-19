<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cms_global_templates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->foreignId('tenant_category_id')->nullable()->constrained('tnt_tenant_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('slug');
            $table->json('brand_settings')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['slug']);

            $table->index(['slug', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_global_templates');
    }
};

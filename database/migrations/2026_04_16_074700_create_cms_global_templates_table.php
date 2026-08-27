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
            $table->foreignId('tenant_category_id')->nullable()->constrained('tnt_tenant_categories')->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('mst_packages')->nullOnDelete();

            // Kolom tier untuk memisahkan template basic/pro
            $table->string('tier')->default('basic')->comment('basic, pro');

            $table->string('title');
            $table->text('description')->nullable();
            $table->jsonb('brand_settings')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->index(['tenant_category_id', 'package_id', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_global_templates');
    }
};

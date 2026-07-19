<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tnt_tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('logo_id')->nullable()->constrained('sys_files')->onDelete('cascade');
            $table->foreignId('favicon_id')->nullable()->constrained('sys_files')->onDelete('cascade');
            $table->foreignId('tenant_category_id')->constrained('tnt_tenant_categories')->onDelete('cascade');
            $table->foreignId('global_template_id')->nullable()->constrained('cms_global_templates')->nullOnDelete();
            $table->string('name');
            $table->string('code')->after('id')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('slug');
            $table->string('custom_domain')->nullable();
            $table->json('settings')->nullable();
            $table->integer('is_suspended')->default(1);

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['name', 'slug']);

            $table->index(['name', 'slug', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tnt_tenants');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cms_global_template_pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('global_template_id')->constrained('cms_global_templates')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->json('content_blocks')->nullable();
            $table->json('meta')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['global_template_id', 'slug']);

            $table->index(['global_template_id', 'slug', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_global_template_pages');
    }
};

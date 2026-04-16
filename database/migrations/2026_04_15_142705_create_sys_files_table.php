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
        Schema::create('sys_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('tenant_id')->nullable();
            $table->integer('related_id')->nullable();
            $table->string('related_type')->nullable();
            $table->string('file_name')->nullable();
            $table->string('original_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('file_extension')->nullable();
            $table->string('file_size')->nullable();
            $table->string('storage_disk')->nullable();
            $table->integer('is_public')->default(1);


            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['tenant_id', 'file_name']);

            $table->index(['tenant_id', 'file_name', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_files');
    }
};

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
        Schema::create('auth_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('guard_name')->default('admin')->comment('admin', 'web', 'mobile');
            $table->string('prefix_label');
            $table->string('module_label');
            $table->string('action_label');
            $table->string('code')->unique();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->index(['module_label', 'action_label', 'prefix_label', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_permissions');
    }
};

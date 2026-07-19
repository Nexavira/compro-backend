<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tnt_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tnt_tenants')->onDelete('cascade');
            $table->string('subscription_number')->unique();

            $table->foreignId('package_id')->constrained('mst_packages')->onDelete('cascade');
            $table->string('package_name');
            $table->string('billing_cycle')->comment('monthly,annually');
            $table->string('status')->comment('active, past_due, canceled');

            $table->date('next_billing_date');
            $table->decimal('amount', 15, 0);

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['tenant_id']);

            $table->index(['tenant_id', 'status', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tnt_subscriptions');
    }
};

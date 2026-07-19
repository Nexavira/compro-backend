<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('trx_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tnt_tenants')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('tnt_subscriptions')->onDelete('cascade');

            $table->string('invoice_number')->unique();
            $table->text('description')->nullable();
            $table->decimal('amount_due', 15, 0);
            $table->date('due_date');
            $table->string('status')->default('unpaid')->comment('unpaid, pending_verification, paid, failed');

            $table->foreignId('proof_of_payment_id')->nullable()->constrained('sys_files')->onDelete('set null');
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('amount_paid', 15, 0)->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->index(['tenant_id', 'subscription_id', 'payment_date', 'status', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_payments');
    }
};

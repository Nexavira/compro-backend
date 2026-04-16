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
        Schema::create('trx_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('subscription_id')->constrained('tnt_subscriptions')->onDelete('cascade');
            $table->foreignId('proof_of_payment_id')->nullable()->constrained('sys_files')->onDelete('set null');
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method');
            $table->unsignedBigInteger('payment_date');

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->index(['subscription_id', 'payment_date', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_payments');
    }
};

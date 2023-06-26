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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->dateTime('trans_date');
            $table->unsignedBigInteger('savings_account_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('gateway_amount', 10, 2)->default(0);
            $table->string('dr_cr', 2);
            $table->string('type', 30);
            $table->string('method', 20);
            $table->tinyInteger('status');
            $table->text('note')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('gateway_id')->nullable()->comment('PayPal | Stripe | Other Gateway');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->text('transaction_details')->nullable();
            $table->string('tracking_id')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

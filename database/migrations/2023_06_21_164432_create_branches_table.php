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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->integer('country_id');
            $table->string('branch_code', 30)->unique();
            $table->string('name', 250);
            $table->string('tax_number', 250)->nullable();
            $table->integer('tax_code_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('discount_code_id')->nullable();
            $table->integer('restaurant_mode')->default(0);
            $table->integer('restaurant_waiter_role_id')->nullable();
            $table->integer('restaurant_billing_type_id')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode', 15)->nullable();
            $table->string('primary_contact', 15)->nullable();
            $table->string('secondary_contact', 15)->nullable();
            $table->string('primary_email', 15)->nullable();
            $table->string('secondary_email', 150)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('enable_customer_popup')->default(0);
            $table->tinyInteger('enable_variants_popup')->default(1);
            $table->tinyInteger('enable_digital_menu_otp_verification')->default(1);
            $table->tinyInteger('digital_menu_send_order_to_kitchen')->default(0);
            $table->integer('menu_language_id')->nullable();
            $table->index(['enable_digital_menu_otp_verification', 'menu_language_id'], 'menu_otp_language_index');
            $table->string('invoice_type', 50)->default('SMALL');
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};

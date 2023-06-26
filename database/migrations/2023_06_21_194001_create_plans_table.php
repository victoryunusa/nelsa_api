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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 13, 2)->default(0);
            $table->json('features')->nullable();
            $table->string('currency', 3)->nullable();
            $table->integer('trial_duration')->default(0); #free trial period
            $table->string('trial_duration_type')->default('day');
            $table->integer('package_duration')->default(0); #how a long the subscription lasts
            $table->string('package_duration_type')->default('month');
            $table->integer('days');
            $table->integer('order_limit');
            $table->integer('product_limit');
            $table->integer('supplier_limit');
            $table->integer('user_limit');
            $table->boolean('is_available')->default(1);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

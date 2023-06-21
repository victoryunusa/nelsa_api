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
        Schema::create('vendor_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('slogan')->nullable();
            $table->string('invoice_print_logo')->nullable();
            $table->text('company_logo')->nullable();
            $table->text('navbar_logo')->nullable();
            $table->text('favicon')->nullable();
            $table->string('phone_number')->nullable();
            $table->longText('description')->nullable();
            $table->string('app_date_time_format', 50);
            $table->string('app_date_format', 50);
            $table->enum('leave_start_from', ['JOINING_DATE', 'YEAR_START'])->default('JOINING_DATE');
            $table->integer('updated_by')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_settings');
    }
};

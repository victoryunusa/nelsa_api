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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 30)->unique();
            $table->string('user_code', 30)->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone', 15)->nullable();
            $table->string('password');
            $table->enum('type', ['STAFF', 'BUSINESS', 'RIDER', 'ADMIN', 'SUPER_ADMIN'])->default('ADMIN');
            $table->enum('gender', ['male', 'female', 'others'])->nullable();
            $table->enum('salutation', ['mr', 'mrs', 'miss', 'dr', 'sir', 'madam'])->nullable();
            $table->enum('login', ['enable', 'disable'])->default('enable');
            $table->text('onesignal_player_id')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('plan_id')->nullable();
            $table->integer('free_plan')->default(0)->nullable();
            $table->integer('allow_without_subscription')->default(0)->nullable();
            $table->date('plan_expire_date')->nullable();
            $table->text('avatar')->nullable();
            $table->string('otp')->nullable();
            $table->string('otp_expire')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('verification_otp_expire')->nullable();
            $table->string('cm_firebase_token')->nullable();
            $table->integer('language_id')->nullable();
            $table->json('preferences')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->boolean('is_activated')->default(0);
            $table->boolean('kyc_fulfilled')->default(0);
            $table->boolean('is_expired')->default(0);
            $table->boolean('is_superadmin')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

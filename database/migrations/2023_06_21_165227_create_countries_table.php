<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('code', 30);
            $table->string('dial_code', 30);
            $table->string('currency_name', 30);
            $table->string('currency_code', 30);
            $table->string('currency_symbol', 30);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => CountrySeeder::class ,
            '--force' => true
        ]);
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};

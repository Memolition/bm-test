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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->string('plate')->unique()->nullable(false);
            $table->unsignedBigInteger('categoryId');
            $table->unsignedBigInteger('userId');

            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('categoryId')->references('id')->on('car_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign('cars_users_userid_foreign');
            $table->dropForeign('cars_car_categories_categoryid_foreign');
        });
        Schema::dropIfExists('cars');
    }
};

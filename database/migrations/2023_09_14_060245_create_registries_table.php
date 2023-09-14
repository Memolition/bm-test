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
        Schema::create('registry', function (Blueprint $table) {
            $table->id();
            $table->date('inAt')->nullable(false);
            $table->date('outAt')->nullable(true)->default(null);
            $table->unsignedBigInteger('carId');
            $table->unsignedBigInteger('userId');

            $table->foreign('carId')->references('id')->on('cars');
            $table->foreign('userId')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign('registry_users_userid_foreign');
            $table->dropForeign('registry_cars_carid_foreign');
        });
        Schema::dropIfExists('registries');
    }
};

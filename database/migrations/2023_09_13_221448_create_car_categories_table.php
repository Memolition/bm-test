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
        Schema::create('car_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->decimal('chargePerMinute', 8, 2)->nullable(false);
            $table->tinyInteger('chargedAt')->nullable(false)->default('1');

            $table->unsignedBigInteger('userId');

            $table->foreign('userId')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_categories', function (Blueprint $table) {
            $table->dropForeign('car_categories_userid_foreign');
        });
        Schema::dropIfExists('car_categories');
    }
};

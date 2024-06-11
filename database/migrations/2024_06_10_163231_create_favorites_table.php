<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('place_id');
            $table->foreign('place_id')->references('id')->on('places')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('favorites', function (Blueprint $table) {
            $table->id()->first();
            $table->unique(['user_id', 'place_id']);
        });
 
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};

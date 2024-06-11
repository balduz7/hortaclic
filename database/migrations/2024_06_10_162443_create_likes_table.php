<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        
        
        Schema::table('likes', function (Blueprint $table) {
            $table->id()->first();
            $table->unique(['user_id', 'post_id']);
        });
 
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};

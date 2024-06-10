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
        
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
       
        Artisan::call('db:seed', [
            '--class' => 'RoleSeeder',
            '--force' => true
         ]);         

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Eliminar la clave foránea de la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        // Eliminar la tabla 'roles'
        Schema::dropIfExists('roles');

      
         
    }
};

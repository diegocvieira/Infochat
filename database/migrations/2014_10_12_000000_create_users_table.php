<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100)->nullable();
            $table->string('imagem', 100)->nullable();
            $table->string('email', 62)->nullable()->unique();
            $table->string('password');
            $table->boolean('online')->default(1);
            $table->boolean('claimed')->default(1);
            $table->string('onesignal_token', 200)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

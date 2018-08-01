<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensagens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('remetente_id');
            $table->foreign('remetente_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('destinatario_id');
            $table->foreign('destinatario_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('lida')->default(0);
            $table->string('mensagem', 5000);
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
        Schema::dropIfExists('mensagens');
    }
}

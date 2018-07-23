<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrabalhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabalhos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('tipo')->comments('0-empresa, 1-profissional');
            $table->string('nome', 100);
            $table->string('slug', 100);
            $table->boolean('status');
            $table->string('imagem', 100)->nullable();
            $table->string('descricao', 10000)->nullable();
            $table->unsignedInteger('cidade_id')->nullable();
            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('cascade');
            $table->string('logradouro', 100)->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('bairro', 50)->nullable();
            $table->string('complemento', 50)->nullable();
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
        Schema::dropIfExists('trabalhos');
    }
}

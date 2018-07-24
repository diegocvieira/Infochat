<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosFuncionamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios_funcionamento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dia');
            $table->time('de_manha')->nullable();
            $table->time('ate_tarde')->nullable();
            $table->time('de_tarde')->nullable();
            $table->time('ate_noite')->nullable();
            $table->unsignedInteger('trabalho_id')->nullable();
            $table->foreign('trabalho_id')->references('id')->on('trabalhos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios_funcionamento');
    }
}

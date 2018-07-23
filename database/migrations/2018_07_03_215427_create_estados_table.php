<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pais_id');
            $table->foreign('pais_id')->references('id')->on('paises');
            $table->integer('region_id');
            $table->string('title', 35);
            $table->char('letter', 2);
            $table->integer('iso');
            $table->tinyInteger('status');
            $table->string('slug', 55);
            $table->char('letter_lc', 2);
            $table->integer('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estados');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo_turnos', function (Blueprint $table) {
            $table->id();
            $table->string('modulo');
            $table->unsignedBigInteger('turno_id');
            $table->date('fecha');

            $table->foreign('turno_id')->references('id')->on('turnos')->onDelete('cascade');
            $table->unique(['modulo', 'fecha']); // Validar que un módulo solo tenga un turno por día
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
        Schema::dropIfExists('modulo_turnos');
    }
}

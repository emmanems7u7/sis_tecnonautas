<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes_asignacion_paramodulos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_u');
            $table->foreign('id_u')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_a');
            $table->foreign('id_a')
                ->references('id')
                ->on('asignacions')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_pm')->nullable();
            $table->foreign('id_pm')
                ->references('id')
                ->on('paralelo_modulos')
                ->onDelete('cascade');

            $table->string('activo', 200);
            $table->integer('nota');
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
        Schema::dropIfExists('estudiantes_por_asignacion_modulos');
    }
};

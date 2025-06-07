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
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pregunta_id');
            $table->foreign('pregunta_id')
                ->references('id')
                ->on('preguntas')
                ->onDelete('cascade');

            $table->unsignedBigInteger('opcion_id')->nullable();
            $table->foreign('opcion_id')
                ->references('id')
                ->on('opcions')
                ->onDelete('cascade');

            $table->string('contenido')->nullable();

            $table->unsignedBigInteger('id_u');
            $table->foreign('id_u')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_e');
            $table->foreign('id_e')
                ->references('id')
                ->on('evaluacions')
                ->onDelete('cascade');

            $table->boolean('correcta')->nullable();
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
        Schema::dropIfExists('respuestas');
    }
};

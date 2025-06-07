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
        Schema::create('respuestas_preguntas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_p');
            $table->foreign('id_p')
                ->references('id')
                ->on('preguntas')
                ->onDelete('cascade');

            $table->string('pregunta', 400);
            $table->integer('correcta');
            $table->integer('marcaest');
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
        Schema::dropIfExists('respuestas_preguntas');
    }
};

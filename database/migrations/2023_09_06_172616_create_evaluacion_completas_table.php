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
        Schema::create('evaluacion_completas', function (Blueprint $table) {
            $table->id();

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

            $table->string('completado');
            $table->string('nota');
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
        Schema::dropIfExists('evaluacion_completas');
    }
};

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
        Schema::create('contenido_temas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_t')->nullable();
            $table->foreign('id_t')
                ->references('id')
                ->on('temas')
                ->onDelete('cascade');

            $table->string('nombre');
            $table->enum('tipo', ['documento', 'video', 'enlace']);
            $table->string('ruta')->nullable();
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
        Schema::dropIfExists('contenido_temas');
    }
};

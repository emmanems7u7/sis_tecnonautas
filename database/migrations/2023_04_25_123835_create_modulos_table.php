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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombreM', 200);
            $table->string('Descripcion', 200);
            $table->string('Duracion', 200);
            $table->string('imagen')->nullable();
            $table->boolean('ultimo_modulo')->default(false);
            $table->timestamps();
        });

        Schema::create('paralelos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->integer("cupo");
            $table->timestamps();
        });

        Schema::create('paralelo_modulos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_p');
            $table->foreign('id_p')
                ->references('id')
                ->on('paralelos')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_m');
            $table->foreign('id_m')
                ->references('id')
                ->on('modulos')
                ->onDelete('cascade');

            $table->integer('inscritos');
            $table->boolean('activo');
            $table->date('mes');
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
        Schema::dropIfExists('modulos');
    }
};

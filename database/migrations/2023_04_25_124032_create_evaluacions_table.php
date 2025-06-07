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
        Schema::create('evaluacions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pm');
            $table->foreign('id_pm')
                ->references('id')
                ->on('paralelo_modulos')
                ->onDelete('cascade');

            $table->string('nombre', 200);
            $table->string('detalle', 200);
            $table->datetime('creado');
            $table->datetime('limite');
            $table->string('completado')->nullable();
            $table->boolean('publicado')->default(false);
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
        Schema::dropIfExists('evaluacions');
    }
};

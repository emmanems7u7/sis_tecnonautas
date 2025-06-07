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
        Schema::create('temas_paramodulos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_t');
            $table->foreign('id_t')
                ->references('id')
                ->on('temas')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_pm');
            $table->foreign('id_pm')
                ->references('id')
                ->on('paralelo_modulos')
                ->onDelete('cascade');

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
        Schema::dropIfExists('temas_por_modulos');
    }
};

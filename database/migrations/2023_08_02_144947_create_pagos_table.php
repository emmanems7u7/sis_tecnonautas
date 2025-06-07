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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('detalle')->nullable();

            $table->unsignedBigInteger('id_tp')->nullable();
            $table->foreign('id_tp')
                ->references('id')
                ->on('tipo_pagos')
                ->onDelete('cascade');

            $table->string('banco')->nullable();
            $table->integer('numero_cuenta')->nullable();
            $table->string('imagen')->nullable();
            $table->string('tipo')->nullable();
            $table->string('email')->unique();
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
        Schema::dropIfExists('pagos');
    }
};

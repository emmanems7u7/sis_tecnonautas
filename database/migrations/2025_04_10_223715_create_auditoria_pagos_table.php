<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auditoria_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pago');
            $table->foreign('id_pago')->references('id')->on('admpagos')->nullable();

            $table->string('estatus');
            $table->json('data_imagen')->nullable();
            $table->json('data_correo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_pagos');
    }
};

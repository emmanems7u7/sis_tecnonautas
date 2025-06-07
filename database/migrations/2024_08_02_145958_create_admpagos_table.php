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
        Schema::create('admpagos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_apm')->nullable();
            $table->foreign('id_apm')
                ->references('id')
                ->on('estudiantes_asignacion_paramodulos')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_a')->nullable();
            $table->foreign('id_a')
                ->references('id')
                ->on('apoderados')
                ->onDelete('cascade');

            $table->string('metodo_pago')->nullable();
            $table->integer('monto')->nullable();
            $table->string('imagenComprobante')->nullable();
            $table->integer('pagado')->nullable();
            $table->date('fecha_pago')->nullable();

            $table->integer('numeroComprobante')->nullable();
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
        Schema::dropIfExists('admpagos');
    }
};

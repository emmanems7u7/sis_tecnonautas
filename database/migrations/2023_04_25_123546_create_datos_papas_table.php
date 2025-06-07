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
        Schema::create('datos_papas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_u');
            $table->foreign('id_u')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('nombrePapa', 200);
            $table->string('nombreMama', 200);
            $table->integer('celPapa');
            $table->integer('celMama');
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
        Schema::dropIfExists('datos_papas');
    }
};

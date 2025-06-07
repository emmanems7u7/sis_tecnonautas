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
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_p');
            $table->foreign('id_p')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('institucion');
            $table->string('carrera');
            $table->string('semestre');
            $table->string('concluido');
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
        Schema::dropIfExists('estudios');
    }
};

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
        Schema::create('eval_por_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_ev');
            $table->foreign('id_ev')
                ->references('id')
                ->on('evaluacions')
                ->onDelete('cascade');

            $table->unsignedBigInteger("id_u");
            $table->foreign('id_u')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer("nota");
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
        Schema::dropIfExists('eval_por_estudiantes');
    }
};

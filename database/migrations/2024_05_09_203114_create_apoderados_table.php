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
        Schema::create('apoderados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_u');
            $table->foreign('id_u')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('foto')->nullable();
            $table->string('parentezco');
            $table->string('nombre');
            $table->string('apepat');
            $table->string('apemat');
            $table->date('fechanac')->nullable();
            $table->integer('ci');
            $table->integer('nit')->nullable();
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
        Schema::dropIfExists('apoderados');
    }
};

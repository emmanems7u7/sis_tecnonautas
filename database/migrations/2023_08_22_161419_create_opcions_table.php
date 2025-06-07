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
        Schema::create('opcions', function (Blueprint $table) {
            $table->id();
            $table->text('texto');
            $table->unsignedBigInteger('preguntas_id');
            $table->boolean('correcta')->default(false);
            $table->timestamps();

            $table->foreign('preguntas_id')
                ->references('id')
                ->on('preguntas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

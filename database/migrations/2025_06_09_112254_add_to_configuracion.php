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
        Schema::table('configuracion', function (Blueprint $table) {
            $table->boolean('mantenimiento')->default(false)->comment('Activar o desactivar mantenimiento de sistema')->after('doble_factor_autenticacion');
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion', function (Blueprint $table) {
            //
        });
    }
};

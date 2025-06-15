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
        Schema::table('user_personalizacions', function (Blueprint $table) {
            $table->string('sidebar_type')->after('sidebar_color')->default('bg-white');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_personalizacions', function (Blueprint $table) {
            //
        });
    }
};

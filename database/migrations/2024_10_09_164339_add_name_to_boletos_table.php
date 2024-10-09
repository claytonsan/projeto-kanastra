<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('boletos', function (Blueprint $table) {
            $table->string('name')->after('governmentId'); // ou 'before' ou onde você quiser
        });
    }

    public function down()
    {
        Schema::table('boletos', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};

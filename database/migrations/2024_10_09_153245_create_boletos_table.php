<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoletosTable extends Migration
{
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id(); // ID  
            $table->string('governmentId')->index(); // número do documento
            $table->string('email'); //email do sacado
            $table->decimal('debtAmount', 10, 2); //valor
            $table->date('debtDueDate'); //data pagamento
            $table->uuid('debtId')->unique(); // darante que o UUID seja único
            $table->timestamps(); // campos created_at e updated_at padrão
        });
    }

    public function down()
    {
        Schema::dropIfExists('boletos');
    }
}

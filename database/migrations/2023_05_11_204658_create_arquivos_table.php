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
        Schema::create('arquivos', function (Blueprint $table) {
            $table->id();
            $table->string('matricula');
            $table->string('cpf');
            $table->string('cod_verba');
            $table->float('valor_solicitado');
            $table->string('valor_realizado');
            $table->string('motivo')->nullable();
            $table->string('mes');
            $table->string('ano');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arquivos');
    }
};

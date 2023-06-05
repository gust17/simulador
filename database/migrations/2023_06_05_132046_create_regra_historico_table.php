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
        Schema::create('regra_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Regra::class);
            $table->string('name');
            $table->date('inicio');
            $table->date('fim')->nullable();
            $table->foreignIdFor(\App\Models\Consignataria::class);
            $table->integer('evento');
            $table->bigInteger('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regra_historico');
    }
};

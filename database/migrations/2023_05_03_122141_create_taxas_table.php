<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxas', function (Blueprint $table) {
            $table->id();
            $table->integer('prazo');
            $table->float('taxa');
            $table->foreignIdFor(\App\Models\Consignataria::class);
            $table->foreignIdFor(\App\Models\Consignante::class);
            $table->foreignIdFor(\App\Models\Regra::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxas');
    }
};

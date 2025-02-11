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
        Schema::create('configuracion_pagos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre_banco", 400);
            $table->string("titular_cuenta", 400);
            $table->string("nro_cuenta", 400);
            $table->string("imagen_qr", 400)->nullable();
            $table->date("fecha_registro");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_pagos');
    }
};

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
        Schema::create('orden_ventas', function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->unique();
            $table->bigInteger("nro")->unique();
            $table->unsignedBigInteger("cliente_id");
            $table->string("nombre_cliente");
            $table->string("apellidos_cliente");
            $table->string("cel");
            $table->string("estado_orden")->default("PENDIENTE"); //PENDIENTE,RECHAZADO,CONFIRMADO
            $table->integer("estado_pago")->default(0);
            $table->unsignedBigInteger("configuracion_pago_id");
            $table->string("comprobante", 255)->nullable();
            $table->text("observacion")->nullable();
            $table->integer("status")->default(1);
            $table->date("fecha_orden");
            $table->date("fecha_confirmacion")->nullable();
            $table->timestamps();

            $table->foreign("cliente_id")->on("clientes")->references("id");
            $table->foreign("configuracion_pago_id")->on("configuracion_pagos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_ventas');
    }
};

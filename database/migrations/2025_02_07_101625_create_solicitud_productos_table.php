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
        Schema::create('solicitud_productos', function (Blueprint $table) {
            $table->id();
            $table->string("codigo_solicitud");
            $table->unsignedBigInteger("nro");
            $table->unsignedBigInteger("sede_id");
            $table->unsignedBigInteger("cliente_id");
            $table->string("nombre_cliente");
            $table->string("apellidos_cliente");
            $table->string("cel");
            $table->string("estado_solicitud")->default("PENDIENTE"); //PENDIENTE,RECHAZADO,CONFIRMADO
            $table->string("estado_seguimiento")->nullable()->default(NULL); //NULL, PENDIENTE, EN PROCESO, EN ALMACÉN, ENTREGADO
            $table->text("observacion")->nullable();
            $table->integer("status")->default(1);
            $table->date("fecha_solicitud");
            $table->date("fecha_verificacion");
            $table->timestamps();

            $table->foreign("cliente_id")->on("clientes")->references("id");
            $table->foreign("sede_id")->on("sedes")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_productos');
    }
};

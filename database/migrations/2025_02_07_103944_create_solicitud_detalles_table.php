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
        Schema::create('solicitud_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("solicitud_producto_id");
            $table->string("nombre_producto", 255);
            $table->string("detalle_producto", 800);
            $table->text("links_referencia");
            $table->timestamps();

            $table->foreign("solicitud_producto_id")->on("solicitud_productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_detalles');
    }
};

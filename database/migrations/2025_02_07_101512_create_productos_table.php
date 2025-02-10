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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("categoria_id");
            $table->string("nombre", 255);
            $table->string("descripcion", 600);
            $table->integer("stock_actual");
            $table->decimal("precio_compra", 24, 2);
            $table->decimal("precio_venta", 24, 2);
            $table->text("observaciones");
            $table->string("publico");
            $table->date("fecha_registro");
            $table->integer("status");
            $table->timestamps();

            $table->foreign("categoria_id")->on("categorias")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

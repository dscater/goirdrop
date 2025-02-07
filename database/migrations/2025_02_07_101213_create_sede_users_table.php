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
        Schema::create('sede_users', function (Blueprint $table) {
            $table->unsignedBigInteger("sede_id");
            $table->unsignedBigInteger("user_id");
            $table->timestamps();

            $table->foreign("sede_id")->on("sedes")->references("id");
            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sede_users');
    }
};

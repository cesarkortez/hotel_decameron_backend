<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // No se permiten hoteles repetidos (podría ser por NIT también)
            $table->string('address');
            $table->string('city');
            $table->string('nit')->unique();
            $table->integer('total_rooms'); // Número máximo de habitaciones del hotel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomConfigurationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('room_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            // Room type: Estándar, Junior, Suite
            $table->enum('room_type', ['Estándar', 'Junior', 'Suite']);
            // Accommodation según el tipo: Sencilla, Doble, Triple, Cuádruple
            $table->enum('accommodation', ['Sencilla', 'Doble', 'Triple', 'Cuádruple']);
            $table->integer('quantity');
            $table->timestamps();
            
            // Evitar duplicados: misma combinación para un hotel
            $table->unique(['hotel_id', 'room_type', 'accommodation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_configurations');
    }
}


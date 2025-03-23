<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Hotel;
use App\Models\RoomConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_se_permite_acomodacion_invalida()
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Test',
            'address' => 'Calle Falsa 123',
            'city' => 'Ciudad',
            'nit' => '000111222-3',
            'total_rooms' => 50
        ]);
        
        $response = $this->postJson("/api/hotels/{$hotel->id}/rooms", [
            'room_type' => 'Estándar',
            'accommodation' => 'Triple', // No permitido para Estándar
            'quantity' => 10
        ]);
        
        $response->assertStatus(422)
                 ->assertJsonStructure(['error']);
    }

    public function test_no_se_permite_exceso_de_habitaciones()
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Test',
            'address' => 'Calle Falsa 123',
            'city' => 'Ciudad',
            'nit' => '000111222-3',
            'total_rooms' => 20
        ]);
        
        // Primera configuración válida
        $this->postJson("/api/hotels/{$hotel->id}/rooms", [
            'room_type' => 'Estándar',
            'accommodation' => 'Sencilla',
            'quantity' => 15
        ])->assertStatus(201);
        
        // Segunda configuración que excedería el total
        $response = $this->postJson("/api/hotels/{$hotel->id}/rooms", [
            'room_type' => 'Suite',
            'accommodation' => 'Doble',
            'quantity' => 10
        ]);
        
        $response->assertStatus(422)
                 ->assertJsonStructure(['error']);
    }
}

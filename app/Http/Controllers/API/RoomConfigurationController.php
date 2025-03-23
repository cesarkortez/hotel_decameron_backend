<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomConfiguration;
use Illuminate\Http\Request;

class RoomConfigurationController extends Controller
{
    // Agregar configuración de habitaciones a un hotel
    public function store(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'room_type'     => 'required|in:Estándar,Junior,Suite',
            'accommodation' => 'required|in:Sencilla,Doble,Triple,Cuádruple',
            'quantity'      => 'required|integer|min:1',
        ]);

        // Validación: Verificar que la combinación de room_type y accommodation sea permitida
        $allowed = [
            'Estándar' => ['Sencilla', 'Doble'],
            'Junior'   => ['Triple', 'Cuádruple'],
            'Suite'    => ['Sencilla', 'Doble', 'Triple'],
        ];
        
        if (!in_array($validated['accommodation'], $allowed[$validated['room_type']])) {
            return response()->json([
                'error' => 'La acomodación seleccionada no es permitida para el tipo de habitación indicado.'
            ], 422);
        }
        
        // Validación: No permitir configuraciones duplicadas (se controla con unique en la migración)
        if ($hotel->roomConfigurations()->where([
            'room_type' => $validated['room_type'],
            'accommodation' => $validated['accommodation']
        ])->exists()) {
            return response()->json([
                'error' => 'Esta combinación de tipo de habitación y acomodación ya existe para este hotel.'
            ], 422);
        }
        
        // Validación: La suma de todas las configuraciones no debe superar el total de habitaciones
        $totalConfigured = $hotel->roomConfigurations()->sum('quantity');
        if (($totalConfigured + $validated['quantity']) > $hotel->total_rooms) {
            return response()->json([
                'error' => 'La cantidad total de habitaciones configuradas supera el máximo permitido para este hotel.'
            ], 422);
        }
        
        $config = $hotel->roomConfigurations()->create($validated);
        
        return response()->json($config, 201);
    }
    
    // Eliminar una configuración de habitación
    public function destroy(Hotel $hotel, RoomConfiguration $configuration)
    {
        // Aseguramos que la configuración pertenezca al hotel
        if ($configuration->hotel_id !== $hotel->id) {
            return response()->json(['error' => 'La configuración no pertenece a este hotel.'], 422);
        }
        
        $configuration->delete();
        return response()->json(null, 204);
    }
}

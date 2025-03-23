<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    // Listar todos los hoteles
    public function index()
    {
        return response()->json(Hotel::with('roomConfigurations')->get());
    }

    // Crear un nuevo hotel
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|unique:hotels,name',
            'address'      => 'required|string',
            'city'         => 'required|string',
            'nit'          => 'required|string|unique:hotels,nit',
            'total_rooms'  => 'required|integer|min:1',
        ]);
        
        $hotel = Hotel::create($validated);
        
        return response()->json($hotel, 201);
    }

    // Mostrar detalles de un hotel
    public function show(Hotel $hotel)
    {
        $hotel->load('roomConfigurations');
        return response()->json($hotel);
    }

    // Actualizar datos de un hotel
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name'         => ['sometimes', 'string', Rule::unique('hotels')->ignore($hotel->id)],
            'address'      => 'sometimes|string',
            'city'         => 'sometimes|string',
            'nit'          => ['sometimes', 'string', Rule::unique('hotels')->ignore($hotel->id)],
            'total_rooms'  => 'sometimes|integer|min:1',
        ]);

        $hotel->update($validated);
        return response()->json($hotel);
    }

    // Eliminar un hotel
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return response()->json(null, 204);
    }
}


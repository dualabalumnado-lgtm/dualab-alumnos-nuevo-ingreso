<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {   
        $user = \App\Models\User::find(1);

        $eventos = $user->eventos;

            return $eventos->map(function ($evento) {
             return [
            'id' => $evento->id,
            'title' => $evento->titulo,
            'start' => $evento->inicio,
            'allDay' => true,
            'descripcion' => $evento->descripcion,
            'backgroundColor' => $evento->color ?? '#59BF38',
            'borderColor' => $evento->color ?? '#1F6935',
            'textColor' => '#ffffff',
             ];
        });
    }
   
    public function store(Request $request)
    {
        $request->validate([
        'titulo' => 'required|string|max:255',
        'inicio' => 'required|date',
        ]);

        $evento = Evento::create([
        'titulo' => $request->titulo,
        'inicio' => $request->inicio,
        'color' => $request->color,
        'descripcion' => $request->descripcion,
        ]);

        
        $evento->users()->attach(1);

        return $evento;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
        ]);

        $evento = Evento::findOrFail($id);

        // 🔒 comprobar relación
        if (!$evento->users->contains(auth()->id())) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $evento->update([
            'titulo' => $request->titulo ?? $evento->titulo,
            'inicio' => $request->inicio ?? $evento->inicio,
            'color' => $request->color ?? $evento->color,
            'descripcion' => $request->descripcion ?? $evento->descripcion,
        ]);

        return $evento;
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);

        if (!$evento->users->contains(auth()->id())) {
        return response()->json(['error' => 'No autorizado'], 403);
        }

        $evento->delete();

        return response()->json(['ok' => true]);
    }
}
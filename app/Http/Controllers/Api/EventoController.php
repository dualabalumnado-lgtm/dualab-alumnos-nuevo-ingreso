<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        return Evento::all()->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->titulo . ($evento->descripcion ? ' - ' . $evento->descripcion : ''),
                'start' => $evento->inicio,
                'descripcion' => $request->descripcion,
                'backgroundColor' => $evento->color ?? '#59BF38',
                'borderColor' => $evento->color ?? '#1F6935',
                'textColor' => '#ffffff',
                
            ];
        });
    }

    public function store(Request $request)
    {
        return Evento::create([
            'titulo' => $request->titulo,
            'inicio' => $request->inicio,
            'color' => $request->color,
            
        ]);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $evento->update([
            'titulo' => $request->titulo ?? $evento->titulo,
            'inicio' => $request->inicio ?? $evento->inicio,
            'color' => $request->color,
        ]);

        return $evento;
    }

    public function destroy($id)
    {
        Evento::destroy($id);
        return response()->json(['ok' => true]);
    }
}
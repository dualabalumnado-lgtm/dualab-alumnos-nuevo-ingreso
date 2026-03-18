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
                'title' => $evento->titulo,
                'start' => $evento->inicio,
            ];
        });
    }

    public function store(Request $request)
    {
        return Evento::create([
            'titulo' => $request->titulo,
            'inicio' => $request->inicio,
        ]);
    }
}
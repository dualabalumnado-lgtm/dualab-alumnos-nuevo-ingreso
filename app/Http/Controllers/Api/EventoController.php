<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $user = \App\Models\User::first();
        if (!$user) {
            return response()->json([]);
        }

        $eventos = $user->eventos()->get();

        return response()->json(
            $eventos->map(function ($evento) {
                return [
                    'id'              => $evento->id,
                    'title'           => $evento->titulo,
                    'start'           => $evento->inicio,
                    'allDay'          => true,
                    'descripcion'     => $evento->descripcion,
                    'backgroundColor' => $evento->color ?? '#59BF38',
                    'borderColor'     => $evento->color ?? '#1F6935',
                    'textColor'       => '#ffffff',
                ];
            })
        );
    }

    public function store(Request $request)
    {
        $evento = Evento::create([
            'titulo'      => $request->titulo,
            'inicio'      => $request->inicio,
            'color'       => $request->color,       // ✅ faltaba
            'descripcion' => $request->descripcion, // ✅ faltaba
        ]);

        $evento->users()->attach(1);

        return response()->json($evento, 201); // ✅ devuelve JSON limpio
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
        ]);

        $evento = Evento::findOrFail($id);

        if (!$evento->users()->where('user_id', 1)->exists()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $evento->update([
            'titulo'      => $request->titulo      ?? $evento->titulo,
            'inicio'      => $request->inicio      ?? $evento->inicio,
            'color'       => $request->color       ?? $evento->color,
            'descripcion' => $request->descripcion ?? $evento->descripcion,
        ]);

        return response()->json($evento, 200); // ✅ devuelve JSON limpio
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);

        if (!$evento->users->contains(1)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $evento->delete();

        return response()->json(['ok' => true]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function index()
    {
        return response()->json(Comentario::all());
    }

    public function store(Request $request)
    {
        $comentario = Comentario::create($request->all());
        return response()->json($comentario, 201);
    }

    public function show($id)
    {
        $comentario = Comentario::find($id);
        return response()->json($comentario);
    }

    public function update(Request $request, $id)
    {
        $comentario = Comentario::find($id);
        $comentario->update($request->all());
        return response()->json($comentario);
    }

    public function destroy($id)
    {
        Comentario::destroy($id);
        return response()->json(['mensaje' => 'Comentario eliminado']);
    }
}
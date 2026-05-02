<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        return response()->json(Curso::all());
    }

    public function store(Request $request)
    {
        $curso = Curso::create($request->all());
        return response()->json($curso, 201);
    }

    public function show($id)
    {
        $curso = Curso::find($id);
        return response()->json($curso);
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::find($id);
        $curso->update($request->all());
        return response()->json($curso);
    }

    public function destroy($id)
    {
        Curso::destroy($id);
        return response()->json(['mensaje' => 'Curso eliminado']);
    }
}
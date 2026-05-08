<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReconocimientoController extends Controller
{
    public function index()
    {
        // Lógica para mostrar la lista de reconocimientos
        return view('vistas.reconocimiento.index');
    }

    public function show($id)
    {
        // Lógica para mostrar un reconocimiento específico
        return view('vistas.reconocimiento.show', compact('id'));
    }
}

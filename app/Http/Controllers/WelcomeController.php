<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use Illuminate\Http\Request;
use App\Models\User;

class WelcomeController extends Controller
{
    function index()
    {
        $usuarios = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'estudiante');
        })->get();


        $materias = Asignacion::with('objetivos', 'caracteristicas', 'beneficios')->get();

        return view('welcome', compact('usuarios', 'materias'));
    }
}

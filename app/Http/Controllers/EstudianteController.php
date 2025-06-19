<?php

namespace App\Http\Controllers;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function index(request $request)
    {
        $search = $request->input('buscar');

        $users = User::role('estudiante')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->paginate(5);

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Usuarios', 'url' => route('users.index')],
        ];
        return view('estudiantes.index', compact('users', 'breadcrumb'));
    }
}

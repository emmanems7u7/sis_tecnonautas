<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tareas_estudiante extends Model
{
    use HasFactory;
    protected $fillable = [
        'archivo',
        'user_id',
        'comentario',
        'tareas_id',
        'nota',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tareas_id');
    }
    public function estudiantes()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

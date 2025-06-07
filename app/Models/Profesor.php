<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_u',
        'mensaje',
        'cargo',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_u'); // 'id_u' es la clave foránea
    }
}

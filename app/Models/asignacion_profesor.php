<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asignacion_profesor extends Model
{
    use HasFactory;
    protected $table = 'asignacion_profesor';
    protected $fillable = [
        'id_pm',
        'id_u',

    ];
    public function paraleloModulo()
    {
        return $this->belongsTo(paralelo_modulo::class, 'id_pm');
    }
    public function profesor()
    {
        return $this->belongsTo(User::class, 'id_u');
    }
}

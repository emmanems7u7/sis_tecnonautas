<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_tp',
        'detalle',
        'banco',
        'numero_cuenta',
        'imagen',
        'email',

    ];
}

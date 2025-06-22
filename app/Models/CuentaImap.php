<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaImap extends Model
{


    protected $fillable = [
        'nombre',
        'host',
        'port',
        'encryption',
        'validate_cert',
        'username',
        'password',
    ];

    protected $casts = [
        'validate_cert' => 'boolean',
    ];
}

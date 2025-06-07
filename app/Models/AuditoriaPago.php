<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaPago extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_pago',
        'estatus',
        'data_imagen',
        'data_correo',
    ];

    protected $casts = [
        'data_imagen' => 'array',
        'data_correo' => 'array',
    ];

    public function pago()
    {
        return $this->belongsTo(Admpago::class, 'id_pago');
    }
}

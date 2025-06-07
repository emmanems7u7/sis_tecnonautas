<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admpago extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_apm',
        'id_a',
        'pagado',
        'metodo_pago',
        'monto',
        'fecha_pago',
        'imagenComprobante',
        'numeroComprobante',

    ];
    public function transacciones()
    {
        return $this->hasMany(AuditoriaPago::class, 'id_pago');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvalParamodulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pm',
        'id_e',
    ];

    public function evaluacionCompleta()
    {
        return $this->hasOne(EvaluacionCompleta::class, 'id_e', 'id_e');
    }
}

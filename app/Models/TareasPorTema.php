<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareasPorTema extends Model
{

    protected $fillable = [
        'id_t',
        'id_ta',
       
    ];


   
    use HasFactory;
}

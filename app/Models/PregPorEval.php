<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PregPorEval extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'id_e',
    'id_p',];
   
}

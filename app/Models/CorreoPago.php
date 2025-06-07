<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorreoPago extends Model
{
    use HasFactory;

    protected $table = 'correo_pagos';

    protected $fillable = ['email'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celulares extends Model
{
    /** @use HasFactory<\Database\Factories\CelularesFactory> */
    use HasFactory;
    protected $fillable = ['modelo','descripcion','precio','marca_id','camara','foto'];
}

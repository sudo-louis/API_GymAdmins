<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'productos';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'proveedor',
        'categoria',
        'cantidad_en_stock',
        'precio',
        'foto',
    ];
}

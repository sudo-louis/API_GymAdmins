<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $primaryKey = 'ID';
    protected $table = 'proveedores';

    protected $fillable = [
        'foto',
        'nombre_empresa',
        'nombre_contacto',
        'telefono',
        'correo',
        'productos_suministrados',
    ];
}

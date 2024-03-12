<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
  use HasFactory;
  protected $table = 'movimientos_inventario';
  protected $primaryKey = 'movimiento_inventario_id';
  const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
  const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de creación
  protected $fillable = [
    'producto_id',
    'tipo',
    'cantidad',
    'registro_autor_id',
    'registro_fecha',
    'venta_id',
  ];
}

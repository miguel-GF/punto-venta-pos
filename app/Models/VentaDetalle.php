<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
  use HasFactory;
  protected $table = 'ventas_detalle';
  protected $primaryKey = 'venta_detalle_id';
  public $timestamps = false;
  const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
  const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización

  protected $fillable = [
    'venta_id',
    'producto_id',
    'total',
    'cantidad',
    'precio_unitario',
    'clave',
    'codigo_barras',
    'producto',
    'registro_autor_id',
    'registro_fecha',
    'actualizacion_fecha',
    'actualizacion_autor_id',
  ];
}

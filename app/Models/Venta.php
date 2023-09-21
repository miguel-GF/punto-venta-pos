<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
  use HasFactory;
  protected $table = 'ventas';
  protected $primaryKey = 'venta_id';
  public $timestamps = false;
  const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
  const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización

  protected $fillable = [
    'cliente_id',
    'folio',
    'serie_folio',
    'total',
    'cantidad',
    'status',
    'registro_autor_id',
    'registro_fecha',
    'actualizacion_fecha',
    'actualizacion_autor_id',
  ];
}

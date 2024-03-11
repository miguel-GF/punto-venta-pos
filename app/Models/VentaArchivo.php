<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaArchivo extends Model
{
  use HasFactory;
  protected $table = 'ventas_archivos';
  protected $primaryKey = 'venta_archivo_id';
  public $timestamps = false;
  const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
  const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización

  protected $fillable = [
    'venta_id',
    'archivo',
    'nombre',
    'extension',
    'status',
    'registro_fecha',
    'actualizacion_fecha',
  ];
}

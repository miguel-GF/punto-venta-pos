<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
  use HasFactory;

  protected $table = 'sucursales';
  protected $primaryKey = 'sucursal_id';
  public $timestamps = false;
  const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
  const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización
  protected $fillable = [
    'folio',
    'clave',
    'nombre',
    'direccion',
    'telefono',
    'rdf',
    'default',
    'correo_fiscal',
    'registro_autor_id',
    'registro_fecha',
    'actualizacion_fecha',
    'actualizacion_autor_id',
  ];
}

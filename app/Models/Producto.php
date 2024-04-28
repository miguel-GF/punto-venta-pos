<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
  use HasFactory;
  protected $table = 'productos';
  protected $primaryKey = 'producto_id';
  public $timestamps = false;
  const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
  const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización

  protected $fillable = [
    'clave',
    'codigo_barras',
    'nombre',
    'descripcion',
    'precio',
    'existencia',
    'status',
    'registro_autor_id',
    'registro_fecha',
    'actualizacion_fecha',
    'actualizacion_autor_id',
    'marca',
    'familia',
    'con_existencia_inicial',
  ];
}

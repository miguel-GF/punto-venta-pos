<?php

namespace App\Models;

use App\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;

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
    'comentario',
  ];

  public static function ponerComentario(string $tipo, stdClass $dataObj)
  {
    $comentario = null;
    switch ($tipo) {
      case Constants::INICIAL_MOVIMIENTO_INVENTARIO_TIPO:
        $comentario = "Movimiento inicial de {$dataObj->cantidad}, del producto {$dataObj->nombre} con clave {$dataObj->clave}";
        break;

      case Constants::ENTRADA_MOVIMIENTO_INVENTARIO_TIPO:
        $comentario = "Entrada de inventario de {$dataObj->cantidad}, del producto {$dataObj->nombre} con clave {$dataObj->clave}";
        break;

      case Constants::SALIDA_MOVIMIENTO_INVENTARIO_TIPO:
        $comentario = "Salida de inventario de {$dataObj->cantidad}, del producto {$dataObj->nombre} con clave {$dataObj->clave}";
        break;

      case Constants::VENTA_MOVIMIENTO_INVENTARIO_TIPO:
        $comentario = "Por venta {$dataObj->folio}, se hace una salida de inventario de {$dataObj->cantidad}, del producto {$dataObj->nombre} con clave {$dataObj->clave}";
        break;
      
      default:
        # code...
        break;
    }
    return $comentario;
  }
}

<?php

namespace App\Repos\Data;

use App\Repos\RH\SucursalRH;
use Illuminate\Support\Facades\DB;

class SucursalRepoData
{
  /**
   * Listar clientes básico
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarBasico(array $filtros)
  {
    $query = DB::table('sucursales as s')
      ->select(
        's.sucursal_id',
        's.clave',
        's.nombre',
        's.direccion',
        's.telefono',
        's.rfc',
        's.status',
        's.default'
      );
    
    SucursalRH::obtenerFiltrosListarBasico($query, $filtros);

    return $query->get()->toArray();
  }
}

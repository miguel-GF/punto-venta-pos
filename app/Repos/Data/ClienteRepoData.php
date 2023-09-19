<?php

namespace App\Repos\Data;

use App\Repos\RH\ClienteRH;
use Illuminate\Support\Facades\DB;

class ClienteRepoData
{
  /**
   * Listar clientes básico
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarBasico(array $filtros)
  {
    $query = DB::table('clientes as c')
      ->select(
        'c.cliente_id',
        'c.nombre_comercial',
        'c.telefono',
        'c.correo',
        'c.status',
      );
    
    ClienteRH::obtenerFiltrosListarBasico($query, $filtros);

    return $query->get()->toArray();
  }
}

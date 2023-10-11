<?php

namespace App\Repos\Data;

use App\Repos\RH\ProductoRH;
use Illuminate\Support\Facades\DB;

class ProductoRepoData
{
  /**
   * Listar productos
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarBasico(array $filtros)
  {
    $query = DB::table('productos as p')
      ->select(
        'p.producto_id',
        'p.clave',
        'p.codigo_barras',
        'p.nombre',
        'p.descripcion',
        'p.precio',
        'p.existencia',
        'p.status',
      );
    
    ProductoRH::obtenerFiltrosListarBasico($query, $filtros);

    return $query->get()->toArray();
  }
}

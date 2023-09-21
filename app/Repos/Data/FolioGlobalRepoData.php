<?php

namespace App\Repos\Data;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FolioGlobalRepoData
{
  /**
   * obtener folio global para update
   *
   * @param  string $tipoFolioGlobal
   * @return mixed
   */
  public static function obtenerFolioGlobalForUpdate(string $tipoFolioGlobal)
  {
    try {
      $query = DB::table('cat_folios_globales')
        ->where('tipo', $tipoFolioGlobal)
        ->lockForUpdate()
        ->first();
      return $query->folio;
    } catch (QueryException $qe) {
      Log::error($qe);
      throw $qe;
    }
  }
}

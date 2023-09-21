<?php

namespace App\Repos\Actions;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FolioGlobalRepoAction
{
  /**
   * Actualizar folio global
   *
   * @param  mixed $update
   * @param  string $tipoFolioGlobal
   * @return void
   */
  public static function actualizar(array $update, string $tipoFolioGlobal)
  {
    try {
      DB::table('cat_folios_globales')
        ->where('tipo', $tipoFolioGlobal)
        ->update($update);
    } catch (QueryException $th) {
      Log::error($th);
      throw $th;
    }
  }
}

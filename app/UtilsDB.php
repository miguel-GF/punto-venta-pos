<?php

namespace App;

use App\Repos\Actions\FolioGlobalRepoAction;
use App\Repos\Data\FolioGlobalRepoData;

class UtilsDB
{
  /**
   * obtenerFolioGlobalForUpdate
   *
   * @param  mixed $tipoFolioGlobal
   * @return mixed
   */
  public static function obtenerFolioGlobalForUpdate(string $tipoFolioGlobal)
  {
    try {
      $folio = FolioGlobalRepoData::obtenerFolioGlobalForUpdate($tipoFolioGlobal);
      $folio = $folio + 1;
      $update['folio'] = $folio;
      FolioGlobalRepoAction::actualizar($update, $tipoFolioGlobal);
      return $folio;
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}

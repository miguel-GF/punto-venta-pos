<?php

namespace App\Exceptions;

use App\Helpers\HelperLog;
use Illuminate\Database\QueryException;
use ArithmeticError;
use DomainException;
use ErrorException;
use PDOException;
use Throwable;

class ExceptionHandler
{
  public static function manejarException(Throwable $th)
  {
    switch ($th) {
      case $th instanceof BusinessException:
        HelperLog::log($th, 'debug');
        break;
      case $th instanceof QueryException:
      case $th instanceof PDOException:
        HelperLog::log($th, 'error');
        break;
      case $th instanceof ArithmeticError:
      case $th instanceof DomainException:
        HelperLog::log($th, 'warning');
        break;
      case $th instanceof PrinterException:
        HelperLog::log($th, 'warning');
      case $th instanceof ErrorException:
        HelperLog::log($th, 'error');
        break;
      default:
        HelperLog::log($th, 'error');
        break;
    }
  }
}

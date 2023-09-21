<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatFolioGlobal extends Model
{
  use HasFactory;
  protected $table = 'cat_folios_globales';
  protected $fillable = [
    'tipo',
    'folio',
  ];
}

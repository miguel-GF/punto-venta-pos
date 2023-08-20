<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    public $timestamps = false;
    const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
    const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización

    protected $fillable = [
        'nombre_comercial',
        'telefono',
        'eslogan',
        'correo',
        'tipo_persona',
        'pais',
        'razon_social',
        'rfc',
        'codigo_postal',
        'domicilio_fiscal',
        'regimen_fiscal_clave',
        'regimen_fiscal_nombre',
        'cfid_clave',
        'cfdi_nombre',
        'correo_fiscal',
        'actualizacion_autor_id',
        'registro_autor_id',
        'registro_fecha',
        'actualizacion_fecha',
        'actualizacion_autor_id',
    ];
}

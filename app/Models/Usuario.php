<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;
    const CREATED_AT = 'registro_fecha'; // Definir el nombre de la columna de fecha de creación
    const UPDATED_AT = 'actualizacion_fecha'; // Definir el nombre de la columna de fecha de actualización

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'registro_autor_id',
        'registro_fecha',
        'actualizacion_autor_id',
        'actualizacion_fecha',
        'impresora_predeterminada',
        'impresora_nombre',
        'impresora_puerto',
        'impresora_ip',
        'lectura_modo_monitor'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
}

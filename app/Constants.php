<?php

namespace App;

class Constants
{
  const PENDIENTE_STATUS = "Pendiente";
  const ACTIVO_STATUS = "Activo";
  const BAJA_STATUS = "Baja";
  const INACTIVO_STATUS = "Inactivo";

  const INICIAL_MOVIMIENTO_INVENTARIO_TIPO = "Inicial";
  const ENTRADA_MOVIMIENTO_INVENTARIO_TIPO = "Entrada";
  const SALIDA_MOVIMIENTO_INVENTARIO_TIPO = "Salida";
  const VENTA_MOVIMIENTO_INVENTARIO_TIPO = "Venta";
  const CAT_FOLIO_GLOBAL_VENTA = "venta";
  const ERROR_DATOS_INVALIDOS = 422;

  const TIPO_FOTO_NORMAL = 'Normal';
  const TIPO_FOTO_THUMBNAIL = 'Thumbnail';
}

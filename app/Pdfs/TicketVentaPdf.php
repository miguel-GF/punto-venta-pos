<?php

namespace App\Pdfs;

use App\UtilsTexto;
use NumberFormatter;
use FPDF;

class TicketVentaPDF extends FPDF
{
  protected $encabezado;
  // Cabecera del ticket
  public function __construct($encabezado = null) {
    parent::__construct();
    $this->encabezado = $encabezado;
  }

  protected function lineaEncabezado ($texto) {
    $this->Cell(0, 16, utf8_decode(strtoupper($texto)), 0, 0, 'C');
    $this->Ln(5);
  }

  function Header()
  {
    $this->SetFont('Arial', 'B', 11);
    self::lineaEncabezado($this->encabezado->nombre);
    
    $direccion = UtilsTexto::quitarAcentos($this->encabezado->direccion);
    if (strlen($direccion) > 26) {
      $dividida = UtilsTexto::dividirCadena($direccion, 26);
      foreach ($dividida as $valor) {
        self::lineaEncabezado($valor);
      }
    } else {
      self::lineaEncabezado($direccion);
    }

    if (!empty($this->encabezado->rfc)) {
      self::lineaEncabezado($this->encabezado->rfc);
    }

    if (!empty($this->encabezado->telefono)) {
      self::lineaEncabezado($this->encabezado->telefono);
    }

    if (!empty($this->encabezado->fecha)) {
      self::lineaEncabezado($this->encabezado->fecha);
    }
  }

  // Contenido del ticket
  function contenido($data)
  {
    $ventaInfo = $data->info;
    $detalles = $data->detalles;
    $headerHeight = $this->GetY();
    $this->SetY($headerHeight + 10);
    $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
    $this->SetFont('Arial', 'B', 9);
    $this->Cell(0, 7, "FOLIO " . $ventaInfo->serie_folio, 0, 0, 'L');
    $this->Ln(4);
    $this->Cell(0, 7, "CLIENTE " .  utf8_decode($ventaInfo->nombre_comercial), 0, 0, 'L');
    $this->Ln(9);
    $this->Cell(25, 7, 'PRODUCTO', 'B', 0, 'L');
    $this->Cell(25, 7, 'CANT', 'B', 0, 'R');
    $this->Cell(25, 7, 'PREC U.', 'B', 1, 'R');
    $this->SetFont('Arial', '', 7);
    foreach ($detalles as $row) {
      $pu = $fmt->formatCurrency($row->precio_unitario, "MXN");
      $total = $fmt->formatCurrency($row->total, "MXN");
      $productoNombre = $row->producto;
      if(strlen($row->producto) > 20) {
        $productoNombre = substr($productoNombre, 0, 20);
      }
      $this->Cell(25, 8, utf8_decode(strtoupper($productoNombre)), 0, 0, 'L');
      $this->Cell(25, 8, $row->cantidad, 0, 0, 'R');
      $this->Cell(25, 8, $pu, 0, 1, 'R');
      $this->Ln(-3);
      $this->Cell(25, 8, "", 0, 0, 'R');
      $this->Cell(25, 8, "TOTAL PREC", 0, 0, 'R');
      $this->Cell(25, 8, $total, 0, 1, 'R');
    }
    $this->Cell(80, 1, '', 'B', 0, 'C');
    $this->Ln(4);
    $this->SetFont('Arial', 'B', 9);
    $totalVenta = $fmt->formatCurrency($ventaInfo->total, "MXN");
    $this->Cell(25, 7, "ARTICULOS   " . $ventaInfo->cantidad, 0, 0, 'L');
    $this->Cell(25, 7, 'TOTAL', 0, 0, 'R');
    $this->Cell(25, 7, $totalVenta, 0, 1, 'R');
  }

  function Footer()
  {
    $headerHeight = $this->GetY();
    $this->SetY($headerHeight + 6);
    if (!empty($this->encabezado->ticket_leyenda_pie)) {
      $leyenda = strtoupper(UtilsTexto::quitarAcentos($this->encabezado->ticket_leyenda_pie));
      if (strlen($leyenda) > 26) {
        $dividida = UtilsTexto::dividirCadena($leyenda, 26);
        foreach ($dividida as $valor) {
          self::lineaEncabezado($valor);
        }
      } else {
        self::lineaEncabezado($leyenda);
      }
    }
  }
}

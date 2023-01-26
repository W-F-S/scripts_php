<?php
namespace setasign\Fpdi\Tcpdf;
require_once(__DIR__.'/../libs/TCPDF/tcpdf.php');
require_once(__DIR__.'/../libs/FPDI-2.3.6/src/FpdiTrait.php');
require_once(__DIR__.'/../libs/FPDI-2.3.6/src/Tcpdf/Fpdi.php');
/*
require_once(__DIR__.'/../libs/FPDI-2.3.6/src/FpdfTplTrait.php');
require_once(__DIR__.'/../libs/FPDI-2.3.6/src/Fpdi.php');
*/
require_once(__DIR__.'/../libs/FPDI-2.3.6/src/autoload.php');
$pdf = new FPDI();
$pdf->setSourceFile(__DIR__.'/../assinatura_php/teste.pdf');
$tplId;
$id = $pdf->importPage(1);
print_r($id);
//$size = $pdf->useImportedPage(tplId, );


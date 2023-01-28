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


define("CERT_DIR", __DIR__.'/../assinatura_php/certificados_de_teste/KRONER MACHADO COSTA 2022.pfx');
define("CERT_PASS", "Kroner1913");

function pfxtocrt(string $pfx, string $password, string $out_path = __DIR__.'/', string $out_name = 'certificado') : array {
  
  if (!$cert_store = file_get_contents($pfx)) {
    echo ("Erro ao pegar o conteudo do arquivo: ".$pfx);
  }
  if (openssl_pkcs12_read($cert_store, $cert_info, $password)) {
    echo "Informação do certificado:";
    print_r($cert_info);
  } else {
    echo "Erro ao tentar formatar o certificado";
    echo openssl_error_string();
  }

  file_put_contents($out_path.$out_name.".crt", $cert_info['cert']);
  file_put_contents($out_path.$out_name.".pem", $cert_info['pkey']);
  file_put_contents($out_path.$out_name."2.crt", $cert_info['cert'].$cert_info['pkey']);
  return [
    "crt" => $out_path . $out_name . ".crt",
    "pem" => $out_path . $out_name . ".pem",
    "crt2" => $out_path . $out_name . "2.crt"
  ];
};



pfxtocrt(CERT_DIR, CERT_PASS);
$pdf = new FPDI();
$pdf->setSourceFile(__DIR__.'/../assinatura_php/teste.pdf');

$id = $pdf->importPage(1);
$size = $pdf->getTemplateSize($id);

print_r($size);

$pdf->AddPage($size['orientation'], array($size['width'], $size['height']), FALSE);
$pdf->useTemplate($id, 0, 0, $size['width'], $size['height'], true);
$pdf->setSignature("file://certificado2.crt", "file://certificado2.crt", '1234', '', 2);
$pdf->Output(__DIR__ . '/tc.pdf', 'F');
//$size = $pdf->useImportedPage(tplId, );


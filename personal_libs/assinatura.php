<?php
	require_once(__DIR__.'/../TCPDF/examples/tcpdf_include.php');

  define("CERT_PATH", __DIR__."/../assinatura_php/certificados_de_teste/Alan Mathison Turing.pfx");

  /**
   * pfxtocrt
   * @param:
   * string $pfx: absolute path para o certifico pfx
   * string $password: password do arquivo pfx
   * string $out_path: absolute path onde o arquivo será colocado
   */
  function pfxtocrt(string $pfx, string $password, string $out_path = __DIR__.'/certificado'){
  
    $cert_info = [];
    $cert_store = '';

    if (!$cert_store = file_get_contents($pfx)) {
      echo ("Erro ao pegar o conteudo do arquivo: ".$pfx);
    }
    //converte o pfx em crt e coloca em $cert_info
    if (openssl_pkcs12_read($cert_store, $cert_info, $password)) {
      echo "Informação do certificado:";
      print_r($cert_info);
    } else {
      echo "Erro ao tentar formatar o certificado";
      echo openssl_error_string();
    }
    file_put_contents($out_path."crt", $cert_info['cert']);
    file_put_contents($out_path."pem", $cert_info['pkey']);
    return [
      "crt" => $out_path . "crt",
      "pem" => $out_path . "pem"
    ];
  };

  fucntion main(){

	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8',false);
	$pdf->SetCreator("Walker");
	$pdf->SetAuthor("Autor");
	$pdf->SetTitle("TCPDF assinatura teste");
	$pdf->SetSubject("TCPDF Tutorial");
	$pdf->SetKeywords("TCPDF, PDF, assinatura, exemplo, teste");

  $pdf->SetHeaderData('',PDF_HEADER_LOGO_WIDTH,"titulo header pdf", "header string");
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  pfxtocrt();

  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


  $certificate = 'file://'.realpath('/data/cert/tcpdf.crt');

    // set additional information
    $info = array(
        'Name' => 'TCPDF',
        'Location' => 'Office',
        'Reason' => 'Testing TCPDF',
        'ContactInfo' => 'http://www.tcpdf.org',
        );
    
    // set document signature
    $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
    
    // set font
    $pdf->SetFont('helvetica', '', 12);
    
    // add a page
    $pdf->AddPage();
    
    // print a line of text
    $text = 'Esse é <b color="#FF0000">um documento digitalmente assinado</b> usando o certificado de <b></b> certificate.<br />To validate this signature you have to load the <b color="#006600">tcpdf.fdf</b> on the Arobat Reader to add the certificate to <i>List of Trusted Identities</i>.<br /><br />For more information check the source code of this example and the source code documentation for the <i>setSignature()</i> method.<br /><br /><a href="http://www.tcpdf.org">www.tcpdf.org</a>';
    $pdf->writeHTML($text, true, 0, true, 0);
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // *** set signature appearance ***
    
    // create content for signature (image and/or text)
    $pdf->Image('images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');
    
    // define active area for signature appearance
    $pdf->setSignatureAppearance(180, 60, 15, 15);
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    // *** set an empty signature appearance ***
    $pdf->addEmptySignatureAppearance(180, 80, 15, 15);
    
    // ---------------------------------------------------------
    
    //Close and output PDF document
    $pdf->Output(__DIR__.'/pdf_exemplo.pdf', 'F');
    
    //============================================================+
    // END OF FILE
    //============================================================+
  }

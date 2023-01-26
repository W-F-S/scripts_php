<?php
$certificado = __DIR__."/../assinatura_php/certificados_de_teste/Alan Mathison Turing.pfx";

if(!$cert_store = file_get_contents($certificado)) {
    echo ("Erro ao pegar o conteudo do arquivo");
}

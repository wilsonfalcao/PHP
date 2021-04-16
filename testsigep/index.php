<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);


require_once 'exemplos/bootstrap-exemplos.php';

$params = include 'exemplos/helper-criar-pre-lista-a4.php';

// Logo da empresa remetente
$logoFile = 'exemplos/logo-etiqueta-2016.png';

//Parametro opcional indica qual layout utilizar para a chancela. Ex.: CartaoDePostagem::TYPE_CHANCELA_CARTA, CartaoDePostagem::TYPE_CHANCELA_CARTA_2016
$layoutChancela = array(); //array(\PhpSigep\Pdf\CartaoDePostagem2016::TYPE_CHANCELA_SEDEX_2016);

$pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($params, time(), $logoFile, $layoutChancela);
$pdf->render();


?>
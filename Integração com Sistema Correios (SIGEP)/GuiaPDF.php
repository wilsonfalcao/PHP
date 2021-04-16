<?php
include 'Service/solicitaetiqueta.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);


//Gerando a guia em PDF para Postar o produto nos correios
$pdf = new \PhpSigep\Pdf\CartaoDePostagem2016x($_SESSION['BodyFormSend'], $_SESSION['PLP'], array());
$pdf->render();

?>
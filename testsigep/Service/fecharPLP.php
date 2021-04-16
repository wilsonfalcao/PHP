<?php 
//Incluindo classe
include 'solicitaetiqueta.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);

$newEtiqueta = $_SESSION['BodyFormSend']->getEncomendas()[0]->getEtiqueta()->getEtiquetaSemDv();
//Enviando o XML via SOAP e fechando a PLP com os dados para envio
$GetNumberPLP = $SOAPHTTPDRequest->fecharPLP('',$newEtiqueta,$_SESSION['XML']);
$_SESSION['PLP'] = $GetNumberPLP->return;
?>

<h1>Código da PLP fechado</h1>

<h2><span style="color:green;font-weight:bold;"> <?php echo $_SESSION['PLP'];?></span></h2>
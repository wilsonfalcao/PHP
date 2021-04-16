<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);

include __DIR__ . '/Service/SOAPClientPLP.php';

$SOAPHTTPDRequest = new SOAPClientPLP();

$viabilidadeReturn = $SOAPHTTPDRequest->disponibilidadeEntrega($_POST);

if($viabilidadeReturn == '0#'){
$_SESSION['POST'] = $_POST;
echo '<a target="_blank" href="Service/etiqueta.php"><button>(1) Solicitar etiqueta</button></a>';
echo '<a target="_blank" href="Service/verificarxml.php"><button>Verificar XML</button></a>';
echo '<a target="_blank" href="Service/fecharPLP.php"><button>(2) Fechar PLP</button></a>';
echo '<a target="_blank" href="GuiaPDF.php"><button>(3) Imprimir Guia</button></a>';
}else{
echo '<h3>Infelizmente o serviço não é disponível para sua área.</h3><br>';
echo $viabilidadeReturn;
}

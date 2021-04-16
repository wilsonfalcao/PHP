<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);

//Incluindo classes de registros e consulta ao serviço SIGEP
require_once __DIR__ .'/SOAPClientPLP.php';
include $_SERVER['DOCUMENT_ROOT'].'/testsigep/exemplos/bootstrap-exemplos.php';
include $_SERVER['DOCUMENT_ROOT'].'/testsigep/Model/PLPBodyService.php';

//Instamciando classes para uso.
$SOAPHTTPDRequest = new SOAPClientPLP();
$GerarXML = new PLPBodyService();

?>


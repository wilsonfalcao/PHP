<?php
require_once __DIR__.'/Service/SOAPClientPLP.php';
$SOAPHTTPDRequest = new SOAPClientPLP();

	function viabilidadeEnvio($remetente,$destino,$codigoservico){
	$viabilidade_envio = $SOAPHTTPDRequest->disponibilidadeEntrega($DPostagem_);

	return $viabilidade_envio;
	}

?>
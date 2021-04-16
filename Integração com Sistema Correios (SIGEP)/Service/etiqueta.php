<?php
include 'solicitaetiqueta.php';
session_start();

/*Solicitando etiqueta, primeira etapa para enviar o produto pelo sistema SIGEP. Tal etiqueta será anexa ao XML para envio via SOAP ao sistema.
O retorno do metodo gerara o código-etiqueta sem o digito verificador. */

//Gerando a etiqueta
$etiqueta = $SOAPHTTPDRequest->solicitaEtiqueta($_SESSION['POST']['servicopost']);

//Criando XML
$params = $GerarXML->body_prelista($_SESSION['POST'],$etiqueta);

//Salvando dados gerados no cookie
$_SESSION['BodyFormSend'] = $params;
$_SESSION['XML'] = $GerarXML->XMLCreated();

?>

<p><h1>Serviço base de solicitação SIGEP:</h1> <?php echo $_SESSION['BodyFormSend']->getEncomendas()[0]->getServicoDePostagem()->getNome();?></p>
<h2><span style="color:green;font-weight:bold;"><?php echo $etiqueta['ComDv'];?></span></h2>
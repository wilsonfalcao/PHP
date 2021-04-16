<?php
include $_SERVER['DOCUMENT_ROOT'].'/testsigep/Model/XMLbodyConstructor.php';
class PLPBodyService {
	
	private $PLPXML;
	
	private function body_impress_plp($bodygeneratorParams,$etiquetas){
	if(!isset($bodygeneratorParams['servid']) || !isset($bodygeneratorParams['servicod'])){ 
	$result = $bodygeneratorParams['servicopost'];
    $result_explode = explode('|', $result);
    $bodygeneratorParams['servicod']  = $result_explode[0];
    $bodygeneratorParams['servid'] = $result_explode[1];
	}
		// ***  DADOS DA ENCOMENDA QUE SERÁ DESPACHADA *** //
    $dimensao = new \PhpSigep\Model\Dimensao();
    $dimensao->setAltura($bodygeneratorParams['altura']);
    $dimensao->setLargura($bodygeneratorParams['largura']);
    $dimensao->setComprimento($bodygeneratorParams['comprimento']);
    $dimensao->setDiametro($bodygeneratorParams['diametro']);
    $dimensao->setTipo($bodygeneratorParams['embalagem']);

    $destinatario = new \PhpSigep\Model\Destinatario();
    $destinatario->setNome($bodygeneratorParams['dnome']);
    $destinatario->setLogradouro($bodygeneratorParams['dlogradouro']);
    $destinatario->setNumero($bodygeneratorParams['dnumero']);
    $destinatario->setComplemento($bodygeneratorParams['dcomplemento']);
	$destinatario->setCelular($bodygeneratorParams['dtelefone']);
	$destinatario->setEmail($bodygeneratorParams['demail']);
		
    $destino = new \PhpSigep\Model\DestinoNacional();
    $destino->setBairro($bodygeneratorParams['dbairro']);
    $destino->setCep($bodygeneratorParams['dcep']);
    $destino->setCidade($bodygeneratorParams['dcidade']);
    $destino->setUf($bodygeneratorParams['destado']);
    $destino->setNumeroNotaFiscal($bodygeneratorParams['nnota']);
    $destino->setNumeroPedido($bodygeneratorParams['registroI']);
	//$destino->setValorACobrar('0');

    // Estamos criando uma etique falsa, mas em um ambiente real voçê deve usar o método
    // {@link \PhpSigep\Services\SoapClient\Real::solicitaEtiquetas() } para gerar o número das etiquetas
    $etiqueta = new \PhpSigep\Model\Etiqueta();
    $etiqueta->setEtiquetaSemDv($etiquetas['SemDv']);
	
    $servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
	if(isset($bodygeneratorParams['recebimento'])){
		$servicoAdicional->setCodigoServicoAdicional($bodygeneratorParams['recebimento']);
		$servicoAdicional->setValorDeclarado(0);
	}
	if(isset($bodygeneratorParams['propria'])){
		$servicoAdicional->setCodigoServicoAdicional($bodygeneratorParams['propria']);
		$servicoAdicional->setValorDeclarado(0);
	}
	if(isset($bodygeneratorParams['declaradosedex'])){
		$servicoAdicional->setCodigoServicoAdicional($bodygeneratorParams['declaradosedex']);
		$servicoAdicional->setValorDeclarado($bodygeneratorParams['vldeclarado']);
	}
	if(isset($bodygeneratorParams['declaradopac'])){
		$servicoAdicional->setCodigoServicoAdicional($bodygeneratorParams['declaradopac']);
		$servicoAdicional->setValorDeclarado($bodygeneratorParams['vldeclarado']);
	}
	if(isset($bodygeneratorParams['registro'])){
		$servicoAdicional->setCodigoServicoAdicional($bodygeneratorParams['registro']);
		$servicoAdicional->setValorDeclarado(0);
	}

    $encomenda = new \PhpSigep\Model\ObjetoPostal();
    $encomenda->setServicosAdicionais(array($servicoAdicional));
    $encomenda->setDestinatario($destinatario);
    $encomenda->setDestino($destino);
    $encomenda->setDimensao($dimensao);
    $encomenda->setEtiqueta($etiqueta);
    $encomenda->setPeso($bodygeneratorParams['pesototal']);// 500 gramas
    $encomenda->setObservacao($bodygeneratorParams['observacao']);
    $encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem($bodygeneratorParams['servicod']));
// ***  FIM DOS DADOS DA ENCOMENDA QUE SERÁ DESPACHADA *** //

// *** DADOS DO REMETENTE *** //
    $remetente = new \PhpSigep\Model\Remetente();
    $remetente->setNome($bodygeneratorParams['rnome']);
    $remetente->setLogradouro($bodygeneratorParams['rlogradouro']);
    $remetente->setNumero($bodygeneratorParams['rnumero']);
    $remetente->setComplemento($bodygeneratorParams['rcomplemento']);
    $remetente->setBairro($bodygeneratorParams['rbairro']);
    $remetente->setCep($bodygeneratorParams['rcep']);
    $remetente->setUf($bodygeneratorParams['restado']);
    $remetente->setCidade($bodygeneratorParams['rcidade']);
	$remetente->setEmail($bodygeneratorParams['remail']);
	$remetente->setTelefone($bodygeneratorParams['rtelefone']);
		
// *** FIM DOS DADOS DO REMETENTE *** //


	$plp = new \PhpSigep\Model\PreListaDePostagem();
	$plp->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
	$plp->setEncomendas(array($encomenda));
	$plp->setRemetente($remetente);
		
	//Criando XML
	$XmlPaser = new XMLbodyConstructor();
	$this->PLPXML = $XmlPaser->returnXml_($plp);
		
	return $plp;
	}
	
	function body_prelista($bodyparams,$etiqueta){
		return $this->body_impress_plp($bodyparams,$etiqueta);
	}
	
	public function XMLCreated(){
		return $this->PLPXML;
	}
}
?>
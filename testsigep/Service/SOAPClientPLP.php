<?php
include $_SERVER['DOCUMENT_ROOT'].'/testsigep/Model/sortitem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/testsigep/exemplos/bootstrap-exemplos.php';

class SOAPClientPLP
{
    protected  $WSDL = 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
	protected $usuario;
	protected $idcontrato;
	protected $senha;
	protected $cartaopostagem;
	protected $CNPJ;
	protected $codigoadministrativo;
	
    function __construct(){

		$data = new \PhpSigep\Model\AccessDataHomologacao();
		
		$this->usuario=	$data->getUsuario();
		$this->idcontrato=	$data->getNumeroContrato();
		$this->senha=	$data->getSenha();
		$this->cartaopostagem=	$data->getCartaoPostagem();
		$this->CNPJ=	$data->getCnpjEmpresa();
		$this->codigoadministrativo=	$data->getCodAdministrativo();
	}
	
    private function soap($methodsoap, $params){
        $soap_config = array(
            'trace' => 1,
            'encoding'=>'ISO-8859-1'
        );
        $client      = new SoapClient($this->WSDL, $soap_config);
        $result      = $client->__soapCall($methodsoap, $params, NULL);
        return $result;
    }
    
    public function buscaCliente() {
        $dataparse = $this->soap("buscaCliente", array(
            "buscaCliente" => array(
                "idContrato" => $this->idcontrato,
                "idCartaoPostagem" => $this->cartaopostagem,
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
        $data      = new sortitem();
        usort($dataparse->return->contratos->cartoesPostagem->servicos, $data->build_sorter('descricao'));
        return $dataparse;
    }
	public function disponibilidadeEntrega($DPostagem_){
		if(!isset($DPostagem_['servicod'])){
		$result = $DPostagem_['servicopost'];
   		$result_explode = explode('|', $result);
    	$DPostagem_['servicod']  = $result_explode[0];
		}
        $dataparse = $this->soap("verificaDisponibilidadeServico", array(
            "verificaDisponibilidadeServico" => array(
				"codAdministrativo"=>$this->codigoadministrativo,
				"numeroServico"=>$DPostagem_['servicod'],
				"cepOrigem"=>$DPostagem_['rcep'],
				"cepDestino"=>$DPostagem_['dcep'],
				"usuario"=>$this->usuario,
				"senha"=>$this->senha,
            )
        ));
        return $dataparse->return;
    }
	public function verificaDisponibilidadeServico($numservico, $arraycep){
        $dataparse = $this->soap("verificaDisponibilidadeServico", array(
            "verificaDisponibilidadeServico" => array(
                "codAdministrativo" => $this->idcontrato,
                "numeroServico" => $numservico,
				"cepOrigem" => $arraycep['origem'],
				"cepDestino" => $arraycep['destino'],
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
        return $dataparse;
    }
	public function solicitaEtiqueta($numservico) {
		$parserv = explode("|",$numservico);
		$numservico = $parserv[1];
        $dataparse = $this->soap("solicitaEtiquetas", array(
            "solicitaEtiquetas" => array(
                "tipoDestinatario" => 'C',
                "identificador" => $this->CNPJ,
				"idServico" => $numservico,
				"qtdEtiquetas" => '1',
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
		$datareturn['SemDv'] = substr($dataparse->return,0,13);
		$datareturn['ComDv'] = $this->digitoVerificador($datareturn['SemDv']);
        return $datareturn;
    }
	public function digitoVerificador($etiqueta){
        $dataparse = $this->soap("geraDigitoVerificadorEtiquetas", array(
            "geraDigitoVerificadorEtiquetas" => array(
                "etiquetas" => $etiqueta,
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
		$dataparse = substr_replace($etiqueta,$dataparse->return, 10,1);
        return $dataparse;
    }
	public function fecharPLP($idPlpCliente,$etiqueta,$xml){
		$xml1 = utf8_encode($xml);
        $dataparse = $this->soap("fechaPlpVariosServicos", array(
            "fechaPlpVariosServicos" => array(
                "xml" => $xml1,
				"idPlpCliente" => $idPlpCliente,
				"cartaoPostagem" => $this->cartaopostagem,
				"listaEtiquetas" => $etiqueta,
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
        return $dataparse;
    }
	public function solicitarPLP($idPlp){
        $dataparse = $this->soap("solicitaXmlPlp", array(
            "solicitaXmlPlp" => array(
				"idPlpMaster" => $idPlp,
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
        return $dataparse;
    }
	public function calcTarifaServico($DPostagem_){
		
		if(!isset($DPostagem_['servicod'])){
		$result = $DPostagem_['servicopost'];
   		$result_explode = explode('|', $result);
    	$DPostagem_['servicod']  = $result_explode[0];
		}
		if(isset($DPostagem_['propria'])){
			$maopropria = "S";
		}else{
			$maopropria = "N";
		}
		if(isset($DPostagem_['recebimento'])){
			$rb = "S";
		}else{
			$rb = "N";
		}
        $dataparse = $this->soap("calculaTarifaServico", array(
            "calculaTarifaServico" => array(
				"codAdministrativo"=>$this->codigoadministrativo,
				"usuario"=>$this->usuario,
				"senha"=>$this->senha,
				"codServico"=>$DPostagem_['servicod'],
				"cepOrigem"=>$DPostagem_['rcep'],
				"cepDestino"=>$DPostagem_['dcep'],
				"peso"=>$DPostagem_['pesototal'],
				"codFormato"=>$DPostagem_['embalagem'],
				"comprimento"=>$DPostagem_['comprimento'],
				"altura"=>$DPostagem_['altura'],
				"largura"=>$DPostagem_['largura'],
				"diametro"=>$DPostagem_['diametro'],
				"codMaoPropria"=>$maopropria,
				"valorDeclarado"=>$DPostagem_['vldeclarado'],
				"codAvisoRecebimento"=>$rb,
            )
        ));
        return $dataparse;
    }
	public function bloquearObjeto($etiquetaComDv,$PLPGerada) {
        $dataparse = $this->soap("bloquearObjeto", array(
            "bloquearObjeto" => array(
                "numeroEtiqueta" => $etiquetaComDv,
				"idPlp" => $PLPGerada,
				"tipoBloqueio" => "FRAUDE_BLOQUEIO",
                "acao" => "DEVOLVIDO_AO_REMETENTE",
                "usuario" => $this->usuario,
                "senha" => $this->senha
            )
        ));
        return $dataparse;
    }
	
}
?>


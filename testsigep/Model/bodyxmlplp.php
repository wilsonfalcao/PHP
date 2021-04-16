<?php

class XMLbodyConstructor
{
	
	public function GeneratorXML($DataPLP){
	return XMLInternal($DataPLP);
	}
	

	private function XMLInternal($datageneratorXML){
		
		$AcessData = $datageneratorXML->getAccessData();
		$Encomenda =$datageneratorXML->getEncomendas()[0];
		$Remetente =$datageneratorXML->getRemetente();
		
		$XMLbody = '<xml><![CDATA[<?xml version="1.0" encoding="ISO-8859-1" ?><correioslog>

					<tipo_arquivo>Postagem</tipo_arquivo>

					<versao_arquivo>2.3</versao_arquivo>

					<plp>

					<id_plp/>

					<valor_global/>

					<mcu_unidade_postagem/>

					<nome_unidade_postagem/>

					<cartao_postagem>'.$this->AcessData->getCartaoPostagem().'</cartao_postagem>

					</plp>

					<remetente>

					<numero_contrato>'.$this->AcessData->getNumeroContrato().'</numero_contrato>

					<numero_diretoria>'.$this->AcessData->getDiretoria().'</numero_diretoria>

					<codigo_administrativo>'.$this->AcessData->getCodAdministrativo().'</codigo_administrativo>

					<nome_remetente>'.$this->Remetente->getNome().'</nome_remetente>

					<logradouro_remetente>'.$this->Remetente->getLogradouro().'</logradouro_remetente>

					<numero_remetente>'.$this->Remetente->getNumero().'</numero_remetente>

					<complemento_remetente>'.$this->Remetente->getComplemento().'</complemento_remetente>

					<bairro_remetente>'.$this->Remetente->getBairro().'</bairro_remetente>

					<cep_remetente>'.$this->Remetente->getCep().'</cep_remetente>

					<cidade_remetente>'.$this->Remetente->getCidade().'</cidade_remetente>

					<uf_remetente>'.$this->Remetente->getUf().'</uf_remetente>

					<telefone_remetente>'.$this->Remetente->getTelefone().'</telefone_remetente>

					<fax_remetente>'.$this->Remetente->getFax().'</fax_remetente>

					<email_remetente>'.$this->Remetente->getEmail().'</email_remetente>

					</remetente>

					<forma_pagamento/>

					<objeto_postal>

					<numero_etiqueta>'.$this->Encomenda->getEtiqueta()->getEtiquetaComDv().'</numero_etiqueta>

					<codigo_objeto_cliente/>

					<codigo_servico_postagem>'.$this->Encomenda->getServicoDePostagem()->getCodigo().'</codigo_servico_postagem>

					<cubagem>0,00</cubagem>

					<peso>'.$this->Encomenda->getPeso().'</peso>

					<rt1/>

					<rt2/>

					<destinatario>

					<nome_destinatario>'.$this->Encomenda->getDestinatario()->getNome().'</nome_destinatario>

					<telefone_destinatario>'.$this->Encomenda->getDestinatario()->getCelular().'</telefone_destinatario>

					<celular_destinatario/><email_destinatario/>

					<logradouro_destinatario>'.$this->Encomenda->getDestinatario()->getLogradouro().'</logradouro_destinatario>

					<complemento_destinatario>'.$this->Encomenda->getDestinatario()->getComplemento().'</complemento_destinatario>

					<numero_end_destinatario>'.$this->Encomenda->getDestinatario()->getNumero().'</numero_end_destinatario>

					</destinatario>

					<nacional>

					<bairro_destinatario>'.$this->Encomenda->getDestino()->getBairro().'</bairro_destinatario>

					<cidade_destinatario>'.$this->Encomenda->getDestino()->getCidade().'</cidade_destinatario>

					<uf_destinatario>'.$this->Encomenda->getDestino()->getUf().'</uf_destinatario>

					<cep_destinatario>'.$this->Encomenda->getDestino()->getCep().'</cep_destinatario>

					<codigo_usuario_postal/>

					<centro_custo_cliente/>

					<numero_nota_fiscal>'.$this->Encomenda->getDestino()->getnumeroNotaFiscal().'</numero_nota_fiscal>

					<serie_nota_fiscal/>

					<valor_nota_fiscal/>

					<natureza_nota_fiscal/>

					<descricao_objeto>'.$this->getObservacao().'</descricao_objeto>

					<valor_a_cobrar>'.$this->getDestino()->getValoraCobrar().'</valor_a_cobrar>

					</nacional>

					<servico_adicional>

					<codigo_servico_adicional>'.$value->getServicosAdicionais()[0]->getCodigoServicoAdicional().'</codigo_servico_adicional>

					<valor_declarado>'.$value->getServicosAdicionais()[0]->getValorDeclarado().'</valor_declarado>

					</servico_adicional>

					<dimensao_objeto>

					<tipo_objeto>'.$this->getDimensao()->getTipo().'</tipo_objeto>

					<dimensao_altura>'.$this->getDimensao()->getAltura().'</dimensao_altura>

					<dimensao_largura>'.$this->getDimensao()->getAltura().'</dimensao_largura>

					<dimensao_comprimento>'.$this->getDimensao()->getComprimento().'</dimensao_comprimento>

					<dimensao_diametro>'.$this->getDimensao()->getDiametro().'</dimensao_diametro>

					</dimensao_objeto>

					<data_postagem_sara/>

					<status_processamento>0</status_processamento>

					<numero_comprovante_postagem/>

					<valor_cobrado/>

					</objeto_postal>

					</correioslog>

					]]></xml>';
		return $XMLbody;
	}
}

?>
<?php

class XMLbodyConstructor
{
	private function XMLInternal($datageneratorXML){
		
		$AcessData = $datageneratorXML->getAccessData();
		$Encomenda =$datageneratorXML->getEncomendas()[0];
		$Remetente =$datageneratorXML->getRemetente();
		
		$XMLbody = '<![CDATA[<?xml version="1.0" encoding="ISO-8859-1" ?><correioslog>

					<tipo_arquivo>Postagem</tipo_arquivo>

					<versao_arquivo>2.3</versao_arquivo>

					<plp>

					<id_plp/>

					<valor_global/>

					<mcu_unidade_postagem/>

					<nome_unidade_postagem/>

					<cartao_postagem>'.$AcessData->getCartaoPostagem().'</cartao_postagem>

					</plp>

					<remetente>

					<numero_contrato>'.$AcessData->getNumeroContrato().'</numero_contrato>

					<numero_diretoria>'.$AcessData->getDiretoria()->getNumero().'</numero_diretoria>

					<codigo_administrativo>'.$AcessData->getCodAdministrativo().'</codigo_administrativo>

					<nome_remetente>'.$Remetente->getNome().'</nome_remetente>

					<logradouro_remetente>'.$Remetente->getLogradouro().'</logradouro_remetente>

					<numero_remetente>'.$Remetente->getNumero().'</numero_remetente>

					<complemento_remetente>'.$Remetente->getComplemento().'</complemento_remetente>

					<bairro_remetente>'.$Remetente->getBairro().'</bairro_remetente>

					<cep_remetente>'.$Remetente->getCep().'</cep_remetente>

					<cidade_remetente>'.$Remetente->getCidade().'</cidade_remetente>

					<uf_remetente>'.$Remetente->getUf().'</uf_remetente>

					<telefone_remetente>'.$Remetente->getTelefone().'</telefone_remetente>

					<fax_remetente>'.$Remetente->getFax().'</fax_remetente>

					<email_remetente>'.$Remetente->getEmail().'</email_remetente>
					
					<celular_remetente>'.$Remetente->getTelefone().'</celular_remetente>
					
					<ciencia_conteudo_proibido>S</ciencia_conteudo_proibido>

					</remetente>

					<forma_pagamento/>

					<objeto_postal>

					<numero_etiqueta>'.$Encomenda->getEtiqueta()->getEtiquetaComDv().'</numero_etiqueta>

					<codigo_objeto_cliente/>

					<codigo_servico_postagem>'.$Encomenda->getServicoDePostagem()->getCodigo().'</codigo_servico_postagem>

					<cubagem>0,00</cubagem>

					<peso>'.$Encomenda->getPeso().'</peso>

					<rt1/>

					<rt2/>
					
					<restricao_anac/>
					
					<destinatario>

					<nome_destinatario>'.$Encomenda->getDestinatario()->getNome().'</nome_destinatario>

					<telefone_destinatario>'.$Encomenda->getDestinatario()->getCelular().'</telefone_destinatario>

					<celular_destinatario>'.$Encomenda->getDestinatario()->getCelular().'</celular_destinatario>
					
					<email_destinatario>'.$Encomenda->getDestinatario()->getEmail().'</email_destinatario>

					<logradouro_destinatario>'.$Encomenda->getDestinatario()->getLogradouro().'</logradouro_destinatario>

					<complemento_destinatario>'.$Encomenda->getDestinatario()->getComplemento().'</complemento_destinatario>

					<numero_end_destinatario>'.$Encomenda->getDestinatario()->getNumero().'</numero_end_destinatario>

					</destinatario>

					<nacional>

					<bairro_destinatario>'.$Encomenda->getDestino()->getBairro().'</bairro_destinatario>

					<cidade_destinatario>'.$Encomenda->getDestino()->getCidade().'</cidade_destinatario>

					<uf_destinatario>'.$Encomenda->getDestino()->getUf().'</uf_destinatario>

					<cep_destinatario>'.$Encomenda->getDestino()->getCep().'</cep_destinatario>

					<codigo_usuario_postal/>

					<centro_custo_cliente/>

					<numero_nota_fiscal>'.$Encomenda->getDestino()->getnumeroNotaFiscal().'</numero_nota_fiscal>

					<serie_nota_fiscal/>

					<valor_nota_fiscal/>

					<natureza_nota_fiscal/>

					<descricao_objeto>'.$Encomenda->getObservacao().'</descricao_objeto>

					<valor_a_cobrar>'.$Encomenda->getDestino()->getValoraCobrar().'</valor_a_cobrar>

					</nacional>

					<servico_adicional>

					<codigo_servico_adicional>'.$Encomenda->getServicosAdicionais()[0]->getCodigoServicoAdicional().'</codigo_servico_adicional>

					<valor_declarado>'.$Encomenda->getServicosAdicionais()[0]->getValorDeclarado().'</valor_declarado>

					</servico_adicional>

					<dimensao_objeto>

					<tipo_objeto>'.$Encomenda->getDimensao()->getTipo().'</tipo_objeto>

					<dimensao_altura>'.$Encomenda->getDimensao()->getAltura().'</dimensao_altura>

					<dimensao_largura>'.$Encomenda->getDimensao()->getAltura().'</dimensao_largura>

					<dimensao_comprimento>'.$Encomenda->getDimensao()->getComprimento().'</dimensao_comprimento>

					<dimensao_diametro>'.$Encomenda->getDimensao()->getDiametro().'</dimensao_diametro>

					</dimensao_objeto>

					<data_postagem_sara/>

					<status_processamento>0</status_processamento>

					<numero_comprovante_postagem/>

					<valor_cobrado/>

					</objeto_postal>

					</correioslog>

					]]>';
		return $XMLbody;
	}
	
	
	private function XMLInternal2($datageneratorXML){
		
		$AcessData = $datageneratorXML->getAccessData();
		$Encomenda =$datageneratorXML->getEncomendas()[0];
		$Remetente =$datageneratorXML->getRemetente();
		
		$xmltoreturn = '<?xml version="1.0" encoding="UTF-8"?><correioslog><tipo_arquivo>Postagem</tipo_arquivo><versao_arquivo>2.3</versao_arquivo><plp>
						<id_plp/><valor_global/><mcu_unidade_postagem/><nome_unidade_postagem/>
						<cartao_postagem>'.$AcessData->getCartaoPostagem().'</cartao_postagem>
						</plp><remetente><numero_contrato>'.$AcessData->getNumeroContrato().'</numero_contrato>
						<numero_diretoria>'.$AcessData->getDiretoria()->getNumero().'</numero_diretoria>
						<codigo_administrativo>'.$AcessData->getCodAdministrativo().'</codigo_administrativo>
						<nome_remetente><![CDATA['.$Remetente->getNome().']]></nome_remetente>
						<logradouro_remetente><![CDATA['.$Remetente->getLogradouro().']]></logradouro_remetente>
						<numero_remetente><![CDATA['.$Remetente->getNumero().']]></numero_remetente>
						<complemento_remetente><![CDATA['.$Remetente->getComplemento().']]></complemento_remetente>
						<bairro_remetente><![CDATA['.$Remetente->getBairro().']]></bairro_remetente>
						<cep_remetente><![CDATA['.$Remetente->getCep().']]></cep_remetente>
						<cidade_remetente><![CDATA['.$Remetente->getCidade().']]></cidade_remetente>
						<uf_remetente><![CDATA['.$Remetente->getUf().']]></uf_remetente>
						<ciencia_conteudo_proibido>S</ciencia_conteudo_proibido>
						</remetente><forma_pagamento/><objeto_postal><numero_etiqueta><![CDATA['.$Encomenda->getEtiqueta()->getEtiquetaComDv().']]></numero_etiqueta>
						<codigo_objeto_cliente/><codigo_servico_postagem><![CDATA['.$Encomenda->getServicoDePostagem()->getCodigo().']]></codigo_servico_postagem>
						<cubagem>0,00</cubagem><peso><![CDATA['.$Encomenda->getPeso().']]></peso><rt1/>
						<rt2/><restricao_anac/><destinatario><nome_destinatario><![CDATA['.$Encomenda->getDestinatario()->getNome().']]></nome_destinatario>
						<logradouro_destinatario><![CDATA['.$Encomenda->getDestinatario()->getLogradouro().']]></logradouro_destinatario>
						<complemento_destinatario><![CDATA['.$Encomenda->getDestinatario()->getComplemento().']]></complemento_destinatario>
						<numero_end_destinatario><![CDATA['.$Encomenda->getDestinatario()->getNumero().']]></numero_end_destinatario>
						</destinatario><nacional><bairro_destinatario><![CDATA['.$Encomenda->getDestino()->getBairro().']]></bairro_destinatario>
						<cidade_destinatario><![CDATA['.$Encomenda->getDestino()->getCidade().']]></cidade_destinatario>
						<uf_destinatario><![CDATA['.$Encomenda->getDestino()->getUf().']]></uf_destinatario>
						<cep_destinatario><![CDATA['.$Encomenda->getDestino()->getCep().']]></cep_destinatario>
						<codigo_usuario_postal/><centro_custo_cliente/><numero_nota_fiscal><![CDATA['.$Encomenda->getDestino()->getnumeroNotaFiscal().']]></numero_nota_fiscal>
						<serie_nota_fiscal/><valor_nota_fiscal/><natureza_nota_fiscal/>
						<descricao_objeto><![CDATA['.$Encomenda->getObservacao().']]></descricao_objeto><valor_a_cobrar><![CDATA['.$Encomenda->getDestino()->getValoraCobrar().']]></valor_a_cobrar></nacional>
						<servico_adicional>'.$this->XMLServicesAdd($Encomenda->getServicosAdicionais()[0]->getCodigoServicoAdicional()).'
						<valor_declarado><![CDATA['.$Encomenda->getServicosAdicionais()[0]->getValorDeclarado().']]></valor_declarado></servico_adicional>
						<dimensao_objeto><tipo_objeto><![CDATA['.$Encomenda->getDimensao()->getTipo().']]></tipo_objeto>
						<dimensao_altura><![CDATA['.$Encomenda->getDimensao()->getAltura().']]></dimensao_altura><dimensao_largura><![CDATA['.$Encomenda->getDimensao()->getAltura().']]></dimensao_largura>
						<dimensao_comprimento><![CDATA['.$Encomenda->getDimensao()->getComprimento().']]></dimensao_comprimento><dimensao_diametro><![CDATA['.$Encomenda->getDimensao()->getDiametro().']]></dimensao_diametro></dimensao_objeto>
						<data_postagem_sara/><status_processamento>0</status_processamento><numero_comprovante_postagem/><valor_cobrado/></objeto_postal></correioslog>';
		
	return $xmltoreturn;
	}
	
	private function XMLServicesAdd($ServicesAdds_){
		$ServiddWrite = "";
		foreach($ServicesAdds_ as $var => $key){
			$ServiddWrite = $ServiddWrite.'<codigo_servico_adicional><![CDATA['.$key.']]></codigo_servico_adicional>';
		}
		return $ServiddWrite;
	}
	
	public function returnXml_($plpParse){
	return $this->XMLInternal2($plpParse);
	}
}
?>
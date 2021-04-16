<?php
include __DIR__ . '/Service/SOAPClientPLP.php';
$result1 = new SOAPClientPLP();
$result  = $result1->buscaCliente();
?>
<form action="gerarplp.php" enctype="multipart/form-data" method="POST" >
<h1>Dados do Rementente</h1>
<div>
<label>Nome</label>
<input name="rnome" type="text" placeholder="Seu nome">
<label>Telefone</label>
<input name="rtelefone" type="text" placeholder="(00) 00000-0000">
<label>E-mail</label>
<input name="remail" type="email" placeholder="nome@dominio.com.br">
</div>
<div>
<label>Logradouro</label>
<input name="rlogradouro" type="text" placeholder="Rua...">
<label>Número</label>
<input name="rnumero" type="text" placeholder="Nº">
<label>Complemento</label>
<input name="rcomplemento" type="text" placeholder="bloco, apartamento, sala">
</div>
<div>
<label>Bairro</label>
<input name="rbairro" type="text" placeholder="">
<label>CEP</label>
<input name="rcep" type="text" placeholder="00000-000">
<label>Cidade</label>
<input name="rcidade" type="text" placeholder="">
<label>Estado</label>
<select name="restado">
	<option value="PE">PE</option><option value="PA">PA</option><option value="PR">PR</option><option value="PB">PB</option>
	<option value="SP">SP</option><option value="CE">CE</option><option value="BA">BA</option><option value="RR">RR</option>
	<option value="MG">MG</option><option value="SJ">SJ</option><option value="ES">ES</option><option value="SE">SE</option>
	<option value="RN">RN</option><option value="TO">TO</option><option value="GO">GO</option><option value="DF">DF</option>
	<option value="RS">RS</option><option value="RO">RO</option><option value="MA">MA</option><option value="GO">GO</option>
	<option value="SC">SC</option><option value="AM">AM</option><option value="MT">MT</option><option value="AC">AC</option>
	<option value="RJ">RJ</option><option value="AP">AP</option><option value="MS">MS</option><option value="PI">PI</option>
</select>
</div>
	<h1>Dados do produto</h1>
<div>
<label>Nota Fiscal</label>
<input name="nnota" type="text" placeholder="número da nota">
<label>Identificação de remessa (Pessoal)</label>
<input name="registroI" type="text" placeholder="Registro Interno">
<div>
<label>Tipo de embalagem</label>
<select name="embalagem">
<option value="001">Envelope</option>
<option value="002">Pacote / Caixa</option>
<option value="003">Rola / Cilindro / Esférico</option>
</select>
</div>
<label>Altura em CM</label>
<input name="altura" type="number" placeholder="5">
<label>Lagura em CM</label>
<input name="largura" type="number" placeholder="7">
<label>Comprimento</label>
<input name="comprimento" type="number" placeholder="7">
<label>Diamentro</label>
<input id="diametro" name="diametro" type="number" value="0">
<label>Peso da encomenda (gramas)</label>
<input name="pesototal" type="number" placeholder="5">
</div>
<h1>Dados do Destinatário</h1>
<div>
<label>Nome</label>
<input name="dnome" type="text" placeholder="Seu nome">
<label>Telefone</label>
<input name="dtelefone" type="text" placeholder="(00) 00000-0000">
<label>E-mail</label>
<input name="demail" type="email" placeholder="nome@dominio.com">	
</div>
<div>
<label>Logradouro</label>
<input name="dlogradouro" type="text" placeholder="Rua...">
<label>Número</label>
<input name="dnumero" type="text" placeholder="Nº">
<label>Complemento</label>
<input name="dcomplemento" type="text" placeholder="bloco, apartamento, sala">
</div>
<div>
<label>Bairro</label>
<input name="dbairro" type="text" placeholder="">
<label>CEP</label>
<input name="dcep" type="text" placeholder="00000-000">
<label>Cidade</label>
<input name="dcidade" type="text" placeholder="">
<label>Estado</label>
<select name="destado">
	<option value="PE">PE</option><option value="PA">PA</option><option value="PR">PR</option><option value="PB">PB</option>
	<option value="SP">SP</option><option value="CE">CE</option><option value="BA">BA</option><option value="RR">RR</option>
	<option value="MG">MG</option><option value="SJ">SJ</option><option value="ES">ES</option><option value="SE">SE</option>
	<option value="RN">RN</option><option value="TO">TO</option><option value="GO">GO</option><option value="DF">DF</option>
	<option value="RS">RS</option><option value="RO">RO</option><option value="MA">MA</option><option value="GO">GO</option>
	<option value="SC">SC</option><option value="AM">AM</option><option value="MT">MT</option><option value="AC">AC</option>
	<option value="RJ">RJ</option><option value="AP">AP</option><option value="MS">MS</option><option value="PI">PI</option>
</select>
</div>
<div>
<h1>Dados do serviço de postagem</h1>
<label>Serviço</label>
<select name="servicopost">
    <option value=""> Escolha o serviço adequado</option>
<?php
foreach ($result->return->contratos->cartoesPostagem->servicos as $data) {
    echo '<option value="' . substr($data->codigo, 0, 5).'|'.$data->id.'">'.$data->descricao . '-'.substr($data->codigo, 0, 5).'</option>';
}
?>
</select>
<label>Observação (opcional)</label>
<textarea name="observacao" placeholder="Digite aqui..."></textarea>
</div>
<h1>Serviços Adicionais</h1>
<div>
<input type="checkbox" name="recebimento" value="001">
<label for="svr1">Aviso de Recebimento</label>
<input type="checkbox" name="propria" value="002">
<label for="svr2">Mão Própria</label>
<input type="checkbox" onClick='CreateInputDeclarado(this.value);' name="declaradosedex" value="019">
<label for="svr3">Declaração de valor SEDEX</label>
<input type="checkbox" onClick='CreateInputDeclarado(this.value);' name="declaradopac" value="064">
<label for="svr4">Declaração de valor PAC</label>
<input type="checkbox" name="registro" value="025">
<label for="svr5">Registro</label>
<div id="divdeclaracao" style="display:none;">
<label for="svr6">Declaração de Valor</label>
<input type='nome' id="declaracao" name='vldeclarado' value='0'>
</div>
</div>
<input type="submit" value="Solicitar Etiquetas">
</form>
<script>
	var declarado =false;
</script>

<script>
function CreateInputDeclarado(value_){
if(value_ == "019" || value_ == "064"){
	if(declarado==false){
document.getElementById("divdeclaracao").style = '';
		declarado =true;
	}else{
	document.getElementById("divdeclaracao").style = 'display:none;';
		declarado =false;
	}
 }
}
</script>
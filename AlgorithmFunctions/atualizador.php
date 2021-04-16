<?php

$JSON = $_POST["JSON"];

function APIPOST($url,$FieldsJSON){
		$FieldsJSON = json_encode($FieldsJSON);
		$url1 = "http://127.0.0.1:8001/api/".$url;
        try{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$FieldsJSON);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93aWxzb25mYWxjYW8ubWxcL2xpdnJvXC9hcGlcL2xvZ2luIiwiaWF0IjoxNjExNjAzNzQ2LCJleHAiOjE2MTE2MDczNDYsIm5iZiI6MTYxMTYwMzc0NiwianRpIjoiZWd5STlNZFJDYlNXelR5eiIsInN1YiI6MSwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.B8K49C98xoIwxqM5WznNj1M6dFHJdmnIsecss0jgu8U',
        'Content-Type:application/json',
        'Content-Length: '.strlen($FieldsJSON)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
		$result = json_decode($result,true);
        }catch(Exception $e){
            return array(["code"=>"400",
                          "mensage"=>$result,
                          "action"=>$url,
                          "mensage2"=>curl_error($curl)]);
        }finally{
            curl_close($curl);
        }
        return $result;
}
function APIGET($url){
		$url1 = "http://127.0.0.1:8001/api/".$url;
        try{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_URL, $url1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93aWxzb25mYWxjYW8ubWxcL2xpdnJvXC9hcGlcL2xvZ2luIiwiaWF0IjoxNjEwMzkzMjc5LCJleHAiOjE2MTAzOTY4NzksIm5iZiI6MTYxMDM5MzI3OSwianRpIjoiSHlHSmVxejc5NnVubWR1cCIsInN1YiI6MSwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.FOLxchCtz-O1DkFL_AI0w2HTuDW0KBbnvpSJX8OTSVE',
        'Content-Type: application/json',));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        $result = json_decode($result,true);
        }catch(Exception $e){
            return array(["code"=>"400",
                          "mensage"=>$result,
                          "action"=>$url,
                          "mensage2"=>curl_error($curl)]);
        }finally{
            curl_close($curl);
        }
        return $result;
}
function GETLINKS($Array){
	
	$ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
	
	if(isset($Array['amazon'])){
	
		if($Array['amazon'][0]['price']['capadura'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capadura'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capadura']) ){
			$ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capadura'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon","desconto"=>$Array['amazon'][0]["desconto"]);
		}
		if($Array['amazon'][0]['price']['capacomum'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capacomum'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capacomum']) ){
			$ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capacomum'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon","desconto"=>$Array['amazon'][0]["desconto"]);
		}
	}
	
	if($Array['Saraiva']['preco'] < $ReturnNewArray['preco'] && $Array['Saraiva']['preco'] != "" && $Array['Saraiva']['preco'] != "0.00" && is_numeric($Array['Saraiva']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['Saraiva']['preco'],"link"=>$Array['Saraiva']['link'],"loja"=>"Saraiva","desconto"=>$Array['Saraiva']['desconto']);
	}
	if($Array['cultura']['preco'] < $ReturnNewArray['preco'] && $Array['cultura']['preco'] != "" && $Array['cultura']['preco'] != "0.00" && is_numeric($Array['cultura']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['cultura']['preco'],"link"=>$Array['cultura']['link'],"loja"=>"Cultura");
	}
	if($Array['americanas']['preco'] < $ReturnNewArray['preco'] && $Array['americanas']['preco'] != "" && $Array['americanas']['preco'] != "0.00" && is_numeric($Array['americanas']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['americanas']['preco'],"link"=>$Array['americanas']['link'],"loja"=>"Americanas","desconto"=>$Array['americanas']['desconto']);
	}
	if($Array['comix']['preco'] <= $ReturnNewArray['preco'] && $Array['comix']['preco'] != "" && $Array['comix']['preco'] != "0.00" && is_numeric($Array['comix']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['comix']['preco'],"link"=>$Array['comix']['link'],"loja"=>"Comix","desconto"=>$Array['comix']['desconto']);
	}
	
	return $ReturnNewArray;
}
function GETLINKS_Amazon($Array){
	
	$ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
	
	if(isset($Array['amazon'])){
	
		if($Array['amazon'][0]['price']['capadura'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capadura'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capadura']) ){
			$ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capadura'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon","desconto"=>$Array['amazon'][0]["desconto"]);
		}
		if($Array['amazon'][0]['price']['capacomum'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capacomum'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capacomum']) ){
			$ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capacomum'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon","desconto"=>$Array['amazon'][0]["desconto"]);
		}
	}
	return $ReturnNewArray;
}
function GETLINKS_Saraiva($Array){
	
	$ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
	
	
	if($Array['Saraiva']['preco'] < $ReturnNewArray['preco'] && $Array['Saraiva']['preco'] != "" && $Array['Saraiva']['preco'] != "0.00" && is_numeric($Array['Saraiva']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['Saraiva']['preco'],"link"=>$Array['Saraiva']['link'],"loja"=>"Saraiva","desconto"=>$Array['Saraiva']['desconto']);
	}
	
	return $ReturnNewArray;
}
function GETLINKS_Cultura($Array){
	
	$ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
	
	if($Array['cultura']['preco'] < $ReturnNewArray['preco'] && $Array['cultura']['preco'] != "" && $Array['cultura']['preco'] != "0.00" && is_numeric($Array['cultura']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['cultura']['preco'],"link"=>$Array['cultura']['link'],"loja"=>"Cultura");
	}
	return $ReturnNewArray;
}
function GETLINKS_Americanas($Array){
	
	$ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
	
	if($Array['americanas']['preco'] < $ReturnNewArray['preco'] && $Array['americanas']['preco'] != "" && $Array['americanas']['preco'] != "0.00" && is_numeric($Array['americanas']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['americanas']['preco'],"link"=>$Array['americanas']['link'],"loja"=>"Americanas","desconto"=>$Array['americanas']['desconto']);
	}
	return $ReturnNewArray;
}
function GETLINKS_Comix($Array){
	
	$ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");

	if($Array['comix']['preco'] < $ReturnNewArray['preco'] && $Array['comix']['preco'] != "" && $Array['comix']['preco'] != "0.00" && is_numeric($Array['comix']['preco']) ){
		$ReturnNewArray = array("preco"=>$Array['comix']['preco'],"link"=>$Array['comix']['link'],"loja"=>"Comix","desconto"=>$Array['comix']['desconto']);
	}
	
	return $ReturnNewArray;
}

$JSON1 = json_decode($JSON,true);

foreach($JSON1['home_book_indice'] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));

	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
		
		$JSON1['home_book_indice'][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1['home_book_indice'][$count]["link"] = $returnLowPrice["link"];
		$JSON1['home_book_indice'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1['home_book_indice'][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1['home_book_indice'][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1['home_book_indice'][$count]["imagem"] =$DATA[0]['image'];
		$JSON1['home_book_indice'][$count]["desconto"] =$returnLowPrice["desconto"];
	}
	$count++;
	   
}
echo "<br><br><h1>England</h1>";

$count = 0;
foreach($JSON1['home_book_england'] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
		
		$JSON1['home_book_england'][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1['home_book_england'][$count]["link"] = $returnLowPrice["link"];
		$JSON1['home_book_england'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1['home_book_england'][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1['home_book_england'][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1['home_book_england'][$count]["imagem"] =$DATA[0]['image'];
		$JSON1['home_book_england'][$count]["desconto"] =$returnLowPrice["desconto"];
	}
	$count++;
	   
}

echo "<br><br><h1>Brasil</h1>";
$count = 0;
foreach($JSON1['home_book_brasil'] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		$JSON1['home_book_brasil'][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1['home_book_brasil'][$count]["link"] = $returnLowPrice["link"];
		$JSON1['home_book_brasil'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1['home_book_brasil'][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1['home_book_brasil'][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1['home_book_brasil'][$count]["imagem"] =$DATA[0]['image'];
		$JSON1['home_book_brasil'][$count]["desconto"] =$returnLowPrice["desconto"];
		echo "<b>Título atualizado: </b>".$JSON1['home_book_brasil'][$count]["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
	}
	$count++;
	   
}

echo "<br><br><h1>Ghost</h1>";
$count = 0;
foreach($JSON1['home_book_ghost'] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){		
		$JSON1['home_book_ghost'][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1['home_book_ghost'][$count]["link"] = $returnLowPrice["link"];
		$JSON1['home_book_ghost'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1['home_book_ghost'][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1['home_book_ghost'][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1['home_book_ghost'][$count]["imagem"] =$DATA[0]['image'];
		$JSON1['home_book_ghost'][$count]["desconto"] =$returnLowPrice["desconto"];
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
	}
	$count++;
	   
}

echo "<br><br><h1>Ireland Saraiva</h1>";
$count = 0;
foreach($JSON1["home_book_ireland"]["xil1"] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS_Saraiva($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
		
		$JSON1["home_book_ireland"]["xil1"][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1["home_book_ireland"]["xil1"][$count]["link"] = $returnLowPrice["link"];
		$JSON1["home_book_ireland"]["xil1"][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1["home_book_ireland"]["xil1"][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1["home_book_ireland"]["xil1"][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1["home_book_ireland"]["xil1"][$count]["imagem"] =$DATA[0]['image'];
		$JSON1["home_book_ireland"]["xil1"][$count]["desconto"] =$returnLowPrice["desconto"];
	}
	$count++;
	   
}

echo "<br><br><h1>Ireland Amazon</h1>";
$count = 0;
foreach($JSON1["home_book_ireland"]["xil3"] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS_Amazon($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
		$JSON1["home_book_ireland"]["xil3"][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1["home_book_ireland"]["xil3"][$count]["link"] = $returnLowPrice["link"];
		$JSON1["home_book_ireland"]["xil3"][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1["home_book_ireland"]["xil3"][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1["home_book_ireland"]["xil3"][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1["home_book_ireland"]["xil3"][$count]["imagem"] =$DATA[0]['image'];
		$JSON1["home_book_ireland"]["xil3"][$count]["desconto"] =$returnLowPrice["desconto"];

	}
	$count++;
	   
}

echo "<br><br><h1>Ireland Cultura</h1>";
$count = 0;
foreach($JSON1["home_book_ireland"]["xil2"] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS_Cultura($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";

		$JSON1["home_book_ireland"]["xil2"][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1["home_book_ireland"]["xil2"][$count]["link"] = $returnLowPrice["link"];
		$JSON1["home_book_ireland"]["xil2"][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1["home_book_ireland"]["xil2"][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1["home_book_ireland"]["xil2"][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1["home_book_ireland"]["xil2"][$count]["imagem"] =$DATA[0]['image'];
		$JSON1["home_book_ireland"]["xil2"][$count]["desconto"] =$DATA[0]['desconto'];
	}
	$count++;
	   
}

echo "<br><br><h1>Subpaginas</h1>";
$count = 0;
foreach($JSON1['template_book_indice'] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
		
		$JSON1['template_book_indice'][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1['template_book_indice'][$count]["link"] = $returnLowPrice["link"];
		$JSON1['template_book_indice'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1['template_book_indice'][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1['template_book_indice'][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1['template_book_indice'][$count]["imagem"] =$DATA[0]['image'];
		$JSON1['template_book_indice'][$count]["desconto"] =$returnLowPrice["desconto"];
	}
	$count++;
	   
}

echo "<br><br><h1>Booksuggest</h1>";
$count = 0;
foreach($JSON1['booksuggest'] as $var){
	$returnLowPrice == null;
	
	if(!isset($var["isbn"])){
		$count++;
		continue;
	}
	   
	//GET DATA
	$DATA = APIPOST("crawling/getbook/full/",array("isbn"=>$var['isbn']));
	
	if(!isset($DATA)){
		$count++;
		continue;
	}
	
	$ISBN13 = $DATA[0]['isbn']['isbn13'];
	$ISBN10 = $DATA[0]['isbn']['isbn10'];

	$cont=1;
	do{
		$PricesBooks = APIPOST("crawling/price/",array("isbn10"=>$ISBN10,"isbn13"=>$ISBN13,"desconto"=>"true"));
		if($cont == 4){
			break;
		}
		sleep(2);
	}while($PricesBooks["amazon"] == "");
	
	
	$returnLowPrice =  GETLINKS($PricesBooks);
	
	if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
		echo "<b>Título atualizado: </b>".$var["titulo"]." <b>de</b> ".$var["preco"]." para ".$returnLowPrice["preco"].", <b>loja</b> ".$var["loja"]." - ".$returnLowPrice["loja"]." com desconto de ".$returnLowPrice["desconto"]; 
		echo "<br><br>";
		
		$JSON1['booksuggest'][$count]["preco"] = $returnLowPrice["preco"];
		$JSON1['booksuggest'][$count]["link"] = $returnLowPrice["link"];
		$JSON1['booksuggest'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
		$JSON1['booksuggest'][$count]["autor"] = $DATA[0]['autor']['name'];
		$JSON1['booksuggest'][$count]["loja"] = $returnLowPrice["loja"];
		$JSON1['booksuggest'][$count]["imagem"] =$DATA[0]['image'];
		$JSON1['booksuggest'][$count]["desconto"] =$returnLowPrice["desconto"];
	}
	$count++;
	   
}

echo "<br><br><br>";

$JSON = json_encode($JSON1);
header('Content-Type: application/json');
echo $JSON;






<?php

namespace App\model\CrawlingModel;

class cultura
{
    //Variavies de retorno
    private $titulo;
    private $autor;
    private $link;
    private $price;
    private $imagem;
    private $url_affiliate;

    function __construct(){
        $this->url_affiliate = "?utmi_cp=12571&utm_source=lomadee&utm_medium=afiliado&utm_content=34118170&utm_campaign=cpa&lmdsid=600436891646-7897-1605097360044";
    }

    public function Search_cultura($term){
        return $this->Cultura_Getbook_Crawler($term);
    }
    public function GetBook_Simple($ISBN){
        return $this->GetBook_Simple_($ISBN);
    }
    public function Cultura_BookSearch($term,$page,$quantidade){
        return $this->Cultura_BookSearch_($term,$page,$quantidade);
    }
    //Código atualizado para versão atual: Cultura_Getbook_Crawler - 29112020
    public function Crawling_internal($term_internal){

        $url = "https://www3.livrariacultura.com.br/livros/".$this->Format_url($term_internal);

        try{
            if (false !== ($data = $datasite = file_get_contents($url))) {

            } else {
                return [""];
            }
        }catch(Exception $e){
            return [""];
        }


        $bodysearch = array();

        $pegarelemento = explode('class="label">Produtos encontrados:</span> <span class="value">', $datasite);
        if(isset($pegarelemento[1])) {
            $livrosachados = explode('</span></span><span', $pegarelemento[1]);
        }else{
            $livrosachados[0] =0;
        }

        if($livrosachados[0]>10){
            $livrosachados[0]= 10;
        }

    for ($i = 1; $i < $livrosachados[0]; $i++) {
    $pegarelemento = explode("<span class='insert-sku-name'> ", $datasite);
    $this->titulo = explode('</span>', $pegarelemento[$i]);

    $pegarelemento = explode('lass="prateleiraProduto__informacao__preco--valor">', $datasite);
    if(isset($pegarelemento[$i])) {
        $this->price = explode('</span>', $pegarelemento[$i]);
    }else{
        $this->price ="0,00";
    }

    $pegarelemento = explode('class="prateleiraProduto__informacao__preco">
            <a href="', $datasite);
    if(isset($pegarelemento[$i])) {
        $this->link = explode('" title="', $pegarelemento[$i]);
    }else{
        $this->link = ['',''];
    }

    $pegarelemento = explode('Colaborador', $datasite);
    if(isset($pegarelemento[1])){
    $pegarelemento1 = explode('</li>', $pegarelemento[$i]);
    }else{
        $pegarelemento1 =[""];
    }

    $pegarelemento = explode('<li class="autor:', $pegarelemento1[0]);
    if(isset($pegarelemento[1])) {
        $this->autor = explode('" >Autor:', $pegarelemento[1]);
    }else{
        $this->autor = ['',''];
    }

    $pegarelemento = explode('<!--			<img src="', $datasite);
    $this->imagem = explode('" width="', $pegarelemento[$i]);

    $this->Formatter();

    $culturabook=['titulo'=>$this->titulo[0],
                  'preco'=>$this->price,
                  'link'=>$this->link[0].$this->url_affiliate,
                  'autor'=>$this->autor,
                  'isbn'=>$this->ISBN($this->link[0]),
                  'imagem'=>strval($this->imagem)];

    array_push($bodysearch, $culturabook);
    $culturabook = null;

    }
    return $bodysearch;
}
    private function Formatter(){

        //Trando dado do preco para decimal
        $this->price = str_replace("R$ ", "", $this->price[0]);
        $this->price = str_replace("\\n", "", $this->price);
        $this->price = str_replace(">\n ", "", $this->price);
        $this->price = str_replace("\n ", "", $this->price);
        $this->price = str_replace(" ", "", $this->price);
        $this->price = str_replace(",", ".", $this->price);
        $this->price = $this->validadorPreco($this->price,8,"0.00");

        //Trando dado Autor para string sem caracteres adicionais
        $this->autor = str_replace("\n", "", $this->autor[0]);
        $this->autor = str_replace(" ", "", $this->autor);
        $this->autor = str_replace("-", " ", $this->autor);
        $this->autor = str_replace("|", "", $this->autor);
        $this->autor = ucwords($this->autor);
        //Trando link de imagem para formato de exbição ajustado
        $this->imagem = str_replace("\/", "/", $this->imagem[0]);
        $this->imagem = str_replace("-300-300", "", $this->imagem);
    }
    private function FormatterSimple(){

        //Trando dado do preco para decimal
        $this->price = str_replace("R$ ", "", $this->price[0]);
        $this->price = str_replace("\\n", "", $this->price);
        $this->price = str_replace(">\n ", "", $this->price);
        $this->price = str_replace("\n ", "", $this->price);
        $this->price = str_replace(" ", "", $this->price);
        $this->price = str_replace(",", ".", $this->price);
        $this->price = $this->validadorPreco($this->price,8,"0.00");
    }
    private function Format_url($query){
        $dataparse = str_replace(" ", "%20", $query);
        return $dataparse;
    }
    private function ISBN($link_){

        if(empty($link_)){
            return [''];
        }

        if (false == ($url = "https://".explode("https://",$link_)[1])){
            $url = $link_;
        }

        $ch = curl_init();
        try{
        
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
        
            $datasite = curl_exec($ch);
            curl_close($ch);

            $pegarelemento = explode('class="value-field ISBN">', $datasite);
            if(isset($pegarelemento[1])){
                $isbnAchado = explode('</td></tr>', $pegarelemento[1]);
            }else{
                return [''];
            }
            return substr($isbnAchado[0],0,10);
        }catch (Exception $e){
            return [''];
        }
    }
    private function GetBook_Simple_($ISBN){

        $url = "https://busca.livrariacultura.com.br/search/?query=".$ISBN."&utmi_cp=12571&utm_source=lomadee&utm_medium=afiliado&utm_content=34118170&utm_campaign=cpa&lmdsid=600436891646-7897-1605097360044";
        
        $ch = curl_init();

        try{
        
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
        
            $datasite = curl_exec($ch);
            curl_close($ch);
        }catch (Exception $e){
            return [''];
        }

        $pegarelemento = explode('"priceText":"', $datasite);
        if(isset($pegarelemento[1])){
        $this->price = explode('"}];', $pegarelemento[1]);
        }else{
            $this->price = ['',''];
        }

        $pegarelemento = explode(',"url":"/', $datasite);
        if(isset($pegarelemento[1])) {
            $this->link = explode('","measurementUnit":"', $pegarelemento[1]);
        }else{
            $this->link = ['',''];
        }

        $this->FormatterSimple();

        $culturabook=['preco'=>$this->price,
                      'link'=>'https://www.livrariacultura.com.br/'.$this->link[0].$this->url_affiliate];
        return $culturabook;
    }
    private function validadorPreco($string,$qtdCaracteres,$erroHipo){
        if(strlen($string)>8){
            return $erroHipo;
        }else{
            return $string;
        }
    }
    private function Cultura_BookSearch_($term_internal,$page,$quantidade){

        $term_internal = $this->Format_url($term_internal);
        $url = "https://busca.livrariacultura.com.br/search/departamento/livros?query=".$term_internal."&page=".$page."&utmi_cp=12571&utm_source=lomadee&utm_medium=afiliado&utm_content=34118170&utm_campaign=cpa&lmdsid=600436891646-7897-1605097360044";
    
        try{
            if (false !== ($data = $datasite = file_get_contents($url))) {

            } else {
                return [""];
            }
        }catch(Exception $e){
            return [""];
        }
    
        $bodysearch = array();

        if(!isset($quantidade)){
            $quantidade= 23;
        }else if($quantidade >24){
            $quantidade = 23;
        }
    
        for ($i = 1; $i <= $quantidade; $i++) {

        $pegarelemento = explode('<span class="insert-sku-name"> ', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->titulo = explode('</span>', $pegarelemento[$i]);
        }else{
            $this->titulo = ['',''];
        }
        
    
        $pegarelemento = explode('"valueText":"', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->price = explode('"},"name":"', $pegarelemento[$i]);
        }else{
            $this->price ="0,00";
        }
    
        $pegarelemento = explode('<div class="prateleiraProduto__informacao__preco">', $datasite);
        if(isset($pegarelemento[$i])) {
            $pegarelemento = explode('<a href="', $pegarelemento[$i]);
        }else{
            $this->link = ['',''];
        }
        if(isset($pegarelemento[1])) {
            $this->link = explode('" title="', $pegarelemento[1]);
        }else{
            $this->link = ['',''];
        }
    
        $pegarelemento = explode('<li class="autor:harari-yuval-noah">Autor:', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->autor = explode('</li>', $pegarelemento[$i]);
        }else{
            $this->autor = ['',''];
        }
    
        $pegarelemento = explode(' <!-- <img src="', $datasite);
        if(isset($pegarelemento[$i])){
        $this->imagem = explode('" width="300" height="300" alt="" id="" /> -->', $pegarelemento[$i]);
        }else{
            $this->imagem = ['',''];
        }
    
        $this->Formatter();
        $this->url_affiliate = "?utmi_cp=12571&utm_source=lomadee&utm_medium=afiliado&utm_content=34118170&utm_campaign=cpa&lmdsid=600436891646-7897-1605097360044";
        $culturabook=['titulo'=>$this->titulo[0],
                      'preco'=>$this->price,
                      'link'=>$this->link[0].$this->url_affiliate,
                      'autor'=>$this->autor,
                      'isbn'=>$this->ISBN($this->link[0]),
                      'imagem'=>strval($this->imagem)];
        if(!empty($this->titulo[0]) && $this->titulo[0] != ""){
            array_push($bodysearch, $culturabook);
        }
        $culturabook = null;
        }
        return $bodysearch;
    }
    public function Cultura_Getbook_Crawler($term_internal){

        $url = "https://busca.livrariacultura.com.br/search/?query=".$this->Format_url($term_internal);

        try{
            if (false !== ($data = $datasite = file_get_contents($url))) {

            } else {
                return [""];
            }
        }catch(Exception $e){
            return [""];
        }


        $bodysearch = array();

        $pegarelemento = explode('Produtos encontrados:', $datasite);
        if(isset($pegarelemento[1])) {
            $livrosachados = explode('</div>', $pegarelemento[1]);
        }else{
            $livrosachados[0] =0;
        }

        if($livrosachados[0]>10){
            $livrosachados[0]= 10;
        }

        for ($i = 1; $i < $livrosachados[0]; $i++) {

        $pegarelemento = explode('"},"name":"', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->titulo = explode('","', $pegarelemento[$i]);
        }else{
            $this->titulo = [""];
        }

        $pegarelemento = explode('"priceText":"R$', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->price = explode('"},{"', $pegarelemento[$i]);
        }else{
            $this->price ="0,00";
        }

        
        $pegarelemento = explode('<h2 class="prateleiraProduto__informacao__nome">', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->link = explode('" title="', $pegarelemento[$i]);
            $this->link = substr(trim($this->link[0]),9,160);
        }else{
            $this->link = ['',''];
        }

        $pegarelemento = explode('<li class="autor:harari-yuval-noah">Autor:', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->autor = explode('</li>', $pegarelemento[$i]);
        }else{
            $this->autor = ['',''];
        }

        $pegarelemento = explode('","value":"', $datasite);
        if(isset($pegarelemento[$i])) {
            $this->imagem = explode('"}],"', $pegarelemento[$i]);
        }else{
            $this->imagem  = ['',''];
        }

        $this->Formatter();

        $culturabook=['titulo'=>$this->titulo[0],
                    'preco'=>$this->price,
                    'link'=>$this->link.$this->url_affiliate,
                    'autor'=>$this->autor,
                    'isbn'=>$this->ISBN($this->link),
                    'imagem'=>strval($this->imagem)];
        if(!empty($this->price) && $this->price != "0.00"){
            array_push($bodysearch, $culturabook);
        }
        $culturabook = null;
        }
        return $bodysearch;
    }
}

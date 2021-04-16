<?php
namespace App\model\CrawlingModel;

class Crawling_Saraiva
{

    //Variavies de retorno
    private $titulo;
    private $autor;
    private $link;
    private $price;
    private $imagem;
    private $desconto;

    public function Search_saraiva($term){
        return $this->Crawling_internal($term);
    }
    public function Get_Categories($Category){
        return $this->Categorie_book($Category);
    }
    public function Getbook_Simple($isbn){
        return $this->Getbook_Simple_($isbn);
    }
    public function Getbook_SimpleDesconto($isbn){
        return $this->Getbook_Simple2($isbn);
    }
    public function Saraiva_BookSearch($term,$page,$qtd){
        return $this->Saraiva_BookSearch_($term,$page,$qtd);
    }

    private function Crawling_internal($internal_term){

        $url = "https://busca.saraiva.com.br/busca?q=" . $this->Format_url($internal_term)."&common_filter%5B1%5D=4";
        
        try{
            if (false !== ($data = $datasite = file_get_contents($url))) {

            } else {
                return [""];
            }
        }catch(Exception $e){
            return [""];
        }

        $bodysearch = array();

        $pegarelemento = explode('<div class="neemu-total-products-container">
                    <span>', $datasite);
        if(isset($pegarelemento[1])) { 
            $livrosachados = explode('</span> produtos                </div>', $pegarelemento[1]);
        }else{
            $livrosachados = ['10'];
        }
        if($livrosachados[0]>10){
            $livrosachados[0]= 10;
        }

        for ($i = 1; $i < $livrosachados[0]; $i++) {
            $pegarelemento = explode('class="nm-product-name">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->titulo = explode('</a>', $pegarelemento[$i]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }

            $pegarelemento = explode('<div class="nm-price-container"', $datasite);
            if(isset($pegarelemento[$i])){
                $this->price = explode('</div>', $pegarelemento[$i]);
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<div class="nm-group-name-authors">
            <a href="//', $datasite);
            if(isset($pegarelemento[$i])){
                $this->link = explode('" title="', $pegarelemento[$i]);
                $this->link = $this->link[0];
            }else{
                $this->link = "";
            }


            $pegarelemento = explode('<div class="nm-product-sub">', $datasite);
            if(isset($pegarelemento[$i])){
                $this->autor = explode('</div>', $pegarelemento[$i]);
                $this->autor = $this->autor[0];
            }else{
                $this->autor = array([""]);
            }

            $pegarelemento2 = explode('<img class="nm-product-img" title="', $datasite);
            if(isset($pegarelemento2[$i])) {
                $this->imagem = explode('" />', $pegarelemento2[$i]);
                $pegarelemento2 = explode('src="', $this->imagem[0]);
                $this->imagem = explode('?v=63', $pegarelemento2[1]);
                $this->imagem = $this->imagem[0];
            }else{
                $this->imagem = "";
            }

            //Chamando metodo de tratamento de dados
            $this->Formatter();

            $saraivabook = ['titulo'=> $this->titulo,
                            'preco' => $this->price,
                            'link'=>strval('https://'.$this->link),
                            'autor'=> $this->autor,
                            'imagem'=>$this->imagem,
                            'isbn'=>$this->_ISBN('https://'.$this->link)];
            if(isset($this->titulo)) {
                array_push($bodysearch, $saraivabook);
            }

            $saraivabook = null;
        }

        return $bodysearch;
    }
    private function Getbook_Simple_($isbnterm){

        if(empty($isbnterm)){
            return [''];
        }

        $url = 'https://busca.saraiva.com.br/busca?q='.$isbnterm;
        
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

            $pegarelemento = explode('<div class="nm-price-container"', $datasite);
            if(isset($pegarelemento[1])){
                $this->price = explode('</div>', $pegarelemento[1]);
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<div class="nm-group-name-authors">
            <a href="//', $datasite);
            if(isset($pegarelemento[1])){
                $this->link = explode('" title="', $pegarelemento[1]);
                $this->link = $this->link[0];
            }else{
                $this->link = "";
            }

            $this->Formatter();

            $saraivabook = ['preco' => $this->price,
                            'link'=>strval('https://'.$this->link)];
        return $saraivabook;
    }
    private function Getbook_Simple2($isbnterm){

        if(empty($isbnterm)){
            return [''];
        }

        $url = 'https://busca.saraiva.com.br/busca?q='.$isbnterm;
        
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

            $pegarelemento = explode('<div class="nm-price-container"', $datasite);
            if(isset($pegarelemento[1])){
                $this->price = explode('</div>', $pegarelemento[1]);
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<div class="nm-group-name-authors">
            <a href="//', $datasite);
            if(isset($pegarelemento[1])){
                $this->link = explode('" title="', $pegarelemento[1]);
                $this->link = $this->link[0];
            }else{
                $this->link = "";
            }

            $pegarelemento = explode('<span class="nm-discount-percent">', $datasite);
            if(isset($pegarelemento[1])){
                $this->desconto= explode('</span>', $pegarelemento[1]);
                $this->desconto = $this->desconto[0];
                $this->desconto = str_replace("%", "", $this->desconto);
                $this->desconto = str_replace("-", "", $this->desconto);
                $this->desconto = str_replace("{{discountPercentage}}", "", $this->desconto);
            }else{
                $this->desconto = "";
            }

            $this->Formatter();

            $saraivabook = ['preco' => $this->price,
                            "desconto"=>$this->desconto,
                            'link'=>strval('https://'.$this->link)];
        return $saraivabook;
    }
    private function Categorie_book($Categorie_term){

        $url = "https://www.saraiva.com.br/livros/".$Categorie_term."?PS=40&O=OrderByTopSaleDESC";

        $datasite = file_get_contents($url);

        $bodysearch = array();

        for($i=1;$i <=20;$i++){

            $pegarelemento= explode('<a class="productImage" title="',$datasite);
            if(isset($pegarelemento[$i])) {
                $this->titulo = explode('" href=', $pegarelemento[$i]);
            }else{
                $this->titulo= "";
            }

            $pegarelemento = explode('<div class="product-field product_field_1364 product-field-type_8">',$datasite);
            $getElement = explode('</ul>', $pegarelemento[$i]);

            if(isset($getElement[1])) {
                $pegarelemento = explode('" >', $getElement[0]);
                $this->autor = explode('</li>', $pegarelemento[1]);
            }else{
                $this->auto= "";
            }

            $pegarelemento= explode('<installment-max price="',$datasite);
            if(isset($pegarelemento[$i])) {
                $this->price = explode('">', $pegarelemento[$i]);
            }else{
                $this->price = '0.00';
            }

            $pegarelemento= explode('<h3 class="product__title limit-block">
        <a href="',$datasite);
            if(isset($pegarelemento[$i])) {
                $this->link = explode('" id="', $pegarelemento[$i]);
            }else{
                $this->link = "";
            }

            $pegarelemento= explode('">
    <img src="https://',$datasite);
            if(isset($pegarelemento[$i])) {
                $this->imagem = explode('" width', $pegarelemento[$i]);
            }else{
                $this->imagem = 'none';
            }

            $this->FormatterCategories();

            $saraivabook = ["titulo"=> $this->titulo[0],
                            "autor"=> $this->autor[0],
                            "preco"=>$this->price,
                            "link" => strval('https://'.$this->link[0]),
                            "imagem"=> strval('https://'.$this->imagem)];

            if(!$this->titulo == null || !$this->titulo == "" || !isset($this->titulo)){
                array_push($bodysearch,$saraivabook);
            }
            $saraivabook = null;
        }
        return $bodysearch;
    }
    private function Saraiva_BookSearch_($term_,$page_,$qtd_){

        $term_ = str_replace(" ","%20",$term_);
        $url = "https://busca.saraiva.com.br/busca?q=".$term_."&page=".$page_."&common_filter%5B1%5D=4";

        $datasite = file_get_contents($url);

        $bodysearch = array();

        if($qtd_ > 23){
            $qtd_ = 23;
        }else if(!isset($qtd_)){
            $qtd_ = 23;
        }

        for ($i = 1; $i <= $qtd_; $i++) {
           
            $pegarelemento = explode('class="nm-product-name">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->titulo = explode('</a>', $pegarelemento[$i]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }

            $pegarelemento = explode('<div class="nm-price-container"', $datasite);
            if(isset($pegarelemento[$i])){
                $this->price = explode('</div>', $pegarelemento[$i]);
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<div class="nm-group-name-authors">
            <a href="//', $datasite);
            if(isset($pegarelemento[$i])){
                $this->link = explode('" title="', $pegarelemento[$i]);
                $this->link = $this->link[0];
            }else{
                $this->link = "";
            }


            $pegarelemento = explode('<div class="nm-product-sub">', $datasite);
            if(isset($pegarelemento[$i])){
                $this->autor = explode('</div>', $pegarelemento[$i]);
                $this->autor = $this->autor[0];
            }else{
                $this->autor = array([""]);
            }

            $pegarelemento2 = explode('<img class="nm-product-img" title="', $datasite);
            if(isset($pegarelemento2[$i])) {
                $this->imagem = explode('" />', $pegarelemento2[$i]);
                $pegarelemento2 = explode('src="', $this->imagem[0]);
                $this->imagem = explode('?v=63', $pegarelemento2[1]);
                $this->imagem = $this->imagem[0];
            }else{
                $this->imagem = "";
            }

            //Chamando metodo de tratamento de dados
            $this->Formatter();

            $saraivabook = ['titulo'=> $this->titulo,
                            'preco' => $this->price,
                            'link'=>strval($this->link),
                            'autor'=> $this->autor,
                            'imagem'=>$this->imagem,
                            'isbn'=>$this->_ISBN("https://".$this->link)];
            if(isset($this->titulo)) {
                array_push($bodysearch, $saraivabook);
            }

            $saraivabook = null;
        }

        return $bodysearch;
    }
    private function _ISBN($link){

        $url = $link;
        
        if(empty($url)){
            return [''];
        }
        try{
            if(empty($datasite = @file_get_contents($url))){
                return [''];
            }
        }catch(Exception $e){
            return [''];
        }

        $bodysearch = array();

        $pegarelemento = explode('<meta itemprop="gtin13" content="', $datasite);
        if(isset($pegarelemento[1])){
            $isbn = explode('" /><meta itemprop=', $pegarelemento[1]);
        }else{
            $isbn = ['',''];
        }

        return $isbn[0];
    }
    private function Formatter(){
        //Tratando dado Preço
        $this->price = str_replace("R$ ", "", $this->price[0]);
        $this->price = str_replace("\\n", "", $this->price);
        $this->price = str_replace(">\n ", "", $this->price);
        $this->price = str_replace("\n ", "", $this->price);
        $this->price = str_replace(" ", "", $this->price);
        $this->price = str_replace(",", ".", $this->price);
        $this->price =$this->validadorPreco($this->price,8,"0.00");

        //Trando dado Autor para string sem caracteres adicionais
        if(isset($this->autor)) {
            $this->autor = str_replace("\n", "", $this->autor);
            $this->autor = str_replace(" ", "", $this->autor);
            //$this->autor = strip_tags($this->autor);
        }else{
            $this->autor = "";
        }

        //Trando link de imagem para formato de exbição ajustado
        if(isset($this->imagem)) {
            $this->imagem = str_replace("-400-400", "", $this->imagem);
        }else{
            $this->imagem ="";
        }
    }
    private function FormatterCategories(){

        //Tratando dado do preco para decimal
        $this->price = str_replace("R$ ","",$this->price[0]);
        $this->price = str_replace("\\n", "", $this->price);
        $this->price = str_replace(">\n ", "", $this->price);
        $this->price = str_replace("\n ", "", $this->price);
        $this->price = str_replace(" ", "", $this->price);
        $this->price = str_replace(",",".",$this->price);

        //Tratando imagem
        $this->imagem = str_replace("\/","/",$this->imagem[0]);
        $this->imagem = str_replace("-221-171","",$this->imagem);
    }
    private function validadorPreco($string,$qtdCaracteres,$erroHipo){
        if(strlen($string)>8){
            return $erroHipo;
        }else{
            return $string;
        }
    }
    private function Format_url($query){
        $dataparse = str_replace(" ", "%20", $query);
        return $dataparse;
    }
}


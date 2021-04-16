<?php

namespace App\model\CrawlingModel;
class comix
{
    //Variavies de retorno
    private $titulo;
    private $price;
    private $link;
    private $autor;
    private $imagem;
    private $_ISBN;
	private $qtd_;
    private $priceespecial;
    private $url_affiliate;
    private $desconto;

    function __construct(){
        $this->url_affiliate = '?utm_source=lomadee&lmdsid=627336779155-10808-1609273962734';
    }
     
    public function SimpleBookSeach($term,$page){
        if($term == "ofertas" || $term == "Ofertas"){
            return $this->OfertasHQs($page);
        }
        return $this->SimpleBookSeach_($term,$page);
    }
    public function GetBook_Simple($term){
        return $this->GetBook_Simple_($term);
    }
    public function GetBook_SimpleDesconto($term){
        return $this->GetBook_Simple2($term);
    }

    private function SimpleBookSeach_($term_,$page_){

        if(empty($term_)){
            return [''];
        }
        
        $term_ = str_replace(" ","+",$term_);

        if(empty($page_)|| $page_ ==""){
            $page_ =1;
        }

        $url = "https://www.comix.com.br/catalogsearch/result/?q=".$term_."&p=".$page_;

        $ch = curl_init();

        $bodysearch = array();

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

        $bodysearch = array();

        if(is_numeric($term_)){
            $qtd_ = 1;
        }else{
            $qtd_ = 48;
        }

        for ($i = 1; $i <= $qtd_; $i++) {
			
			$elementoHQs = explode('<div class="item-content">', $datasite);
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[$i]);
			
			$pegarelemento = explode('" title="', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->titulo = explode('" class="', $pegarelemento[1]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }
			
			$pegarelemento = explode('<a href="', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->link = explode('" title="', $pegarelemento[1]);
                $this->link = $this->link[0];
            }else{
                $this->link = "";
            }
			
			$elementoHQs = explode('<div class="price-box">', $datasite);
            $pegarelemento = explode('<p class="new-price">', $elementoHQs[$i]);
			
			$pegarelemento = explode('<span class="price"', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->price = explode('</span>', $pegarelemento[1]);
                $this->price = explode("R$",$this->price[0])[1];
            }else{
				
            }
			
			$elementoHQs = explode('<div class="price-box">', $datasite);
            $pegarelemento = explode('<p class="new-price">', $elementoHQs[$i]);
			
			$pegarelemento = explode('Preço Promocional</span>', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->priceespecial = explode('</span>', $pegarelemento[1])[0];
            }else{
				$this->priceespecial = "";
            }
			
			$elementoHQs = explode('<div class="item-content">', $datasite);
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[$i]);
			
			$pegarelemento = explode('src="', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->imagem = explode('" width="135"', $pegarelemento[1]);
                $this->imagem = $this->imagem[0];
            }else{
                $this->imagem = "";
            }

            $this->Formatter();
            
            $saraivabook = ['titulo'=> $this->titulo,
                            'preco' => $this->price,
                            'link'=>strval($this->link).$this->url_affiliate,
							'priceespecial'=>$this->priceespecial,
                            'imagem'=>$this->imagem];
            if(isset($this->titulo) ||$this->titulo != "") {
                array_push($bodysearch, $saraivabook);
            }
        }

        return $bodysearch;
    }
    private function GetISBNHQ($link_){

        if(empty($link_)){
            return [''];
        }
        $url = $link_;

        $ch = curl_init();

        $bodysearch = array();

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
			
		$elementoHQs = explode('<th class="label">Código Identificador (SKU)</th>', $datasite);
        $pegarelemento = explode('<th class="label">', $elementoHQs[1]);
		
		$pegarelemento = explode('<td class="data">', $pegarelemento[0]);
        if(isset($pegarelemento[1])) {
            $isbn13 = explode('</td>', $pegarelemento[1]);
            $isbn13 = $isbn13[0];
        }else{
            $isbn13 = "";
        }
        return $isbn13;
    }
    private function GetBook_Simple_($isbn_){

        if(empty($isbn_)){
            return [''];
        }

        $url = 'https://www.comix.com.br/catalogsearch/result/?q='.$isbn_;

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

        $elementoHQs = explode('<div class="item-content">', $datasite);
        if(isset($elementoHQs[1])){
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[1]);
        }else{
            return ['preco' => "0.00",'link'=>""];
        }
        
        $pegarelemento = explode('<a href="', $pegarelemento[0]);
        if(isset($pegarelemento[1])) {
            $this->link = explode('" title="', $pegarelemento[1]);
            $this->link = $this->link[0];
        }else{
            $this->link = "";
        }
        
        $elementoHQs = explode('<span class="regular-price"', $datasite);
        if(isset($elementoHQs[1])){
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[1]);
            
            $pegarelemento = explode('<span class="price">', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->price = explode('</span>', $pegarelemento[1]);
                $this->price = explode("R$",$this->price[0])[1];
                $this->price = str_replace(",",".",$this->price);
            }else{
                $this->price = "0.00";
            }
        }
        $elementoHQs = explode('<div class="price-box">', $datasite);
        $pegarelemento = explode('<p class="new-price">', $elementoHQs[1]);
        
        $pegarelemento = explode('Preço Promocional</span>', $pegarelemento[0]);
        if(isset($pegarelemento[1])) {
            $this->priceespecial = explode('</span>', $pegarelemento[1])[0];
            $this->priceespecial = strip_tags($this->priceespecial);
            $this->priceespecial = str_replace("\n", "", $this->priceespecial);
            $this->priceespecial = str_replace(" ", "", $this->priceespecial);
            $this->priceespecial = str_replace("R$", "", $this->priceespecial);
            $this->priceespecial = str_replace(",", ".", $this->priceespecial);
        }else{
            $this->priceespecial = "0.00";
        }

        if(!empty($this->priceespecial) && $this->priceespecial !="0.00"){
            $this->price = $this->priceespecial;
        }

        if(!empty($this->price)){
            $comixbook= ['preco' => $this->price,'link'=>strval($this->link).$this->url_affiliate];
            return $comixbook;
        }
        return "";
    }
    private function GetBook_Simple2($isbn_){

        if(empty($isbn_)){
            return [''];
        }

        $url = 'https://www.comix.com.br/catalogsearch/result/?q='.$isbn_;

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

        $elementoHQs = explode('<div class="item-content">', $datasite);
        if(isset($elementoHQs[1])){
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[1]);
        }else{
            return ['preco' => "0.00",'link'=>"","desconto"=>""];
        }
        
        $pegarelemento = explode('<a href="', $pegarelemento[0]);
        if(isset($pegarelemento[1])) {
            $this->link = explode('" title="', $pegarelemento[1]);
            $this->link = $this->link[0];
        }else{
            $this->link = "";
        }
        
        $elementoHQs = explode('<div class="price-box">', $datasite);
        $pegarelemento = explode('<div class="short-description">', $elementoHQs[1]);
        
        $pegarelemento = explode('<span class="price">', $pegarelemento[0]);
        if(isset($pegarelemento[1])) {
            $this->price = explode('</span>', $pegarelemento[1]);
            $this->price = trim($this->price[0]);
            $this->price = explode("R$",$this->price[0])[1];
            $this->price = str_replace(",",".",$this->price);
        }else{
            $this->price = "0.00";
        }
        
        $elementoHQs = explode('<div class="price-box">', $datasite);
        $pegarelemento = explode('<p class="new-price">', $elementoHQs[1]);
        
        $pegarelemento = explode('Preço Promocional</span>', $pegarelemento[0]);
        if(isset($pegarelemento[1])) {
            $this->priceespecial = explode('</span>', $pegarelemento[1])[0];
            $this->priceespecial = trim($this->priceespecial);
        }else{
            $this->priceespecial = "0.00";
        }

        $pegarelemento = explode("Você economiza: <span class='price'>", $datasite);
        if(isset($pegarelemento[1])) {
            $this->desconto = explode('</span>', $pegarelemento[1])[0];
            $this->desconto = explode('(', $this->desconto)[1];
            $this->desconto = str_replace("\\n", "", $this->desconto);
            $this->desconto = str_replace("%", "", $this->desconto);
            $this->desconto = str_replace(")", "", $this->desconto);
        }else{
            $this->desconto = "0.00";
        }

        $this->Formatter();

        if(!empty($this->priceespecial) && $this->priceespecial !="0.00"){
            $this->price = $this->priceespecial;
        }

        if(!empty($this->price)){
        $comixbook= ['preco' => $this->price,
                     'desconto'=>$this->desconto,
                     'link'=>strval($this->link).$this->url_affiliate];
        return $comixbook;
        }
        return "";
    }
    private function OfertasHQs($page_){
    
        if(empty($page_)|| $page_ ==""){
            $page_ =1;
        }

        $url = "http://www.comix.com.br/ofertas/?p=".$page_;

        $ch = curl_init();

        $bodysearch = array();

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

        $bodysearch = array();

        $qtd_ = 48;

        for ($i = 1; $i <= $qtd_; $i++) {
			
			$elementoHQs = explode('<div class="sp"></div>', $datasite);
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[$i]);
			
			$pegarelemento = explode('height="135" alt="', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->titulo = explode('" /></a>', $pegarelemento[1]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }
			
			$pegarelemento = explode('<a href="', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->link = explode('" title="', $pegarelemento[1]);
                $this->link = $this->link[0];
            }else{
                $this->link = "";
            }
			
			$elementoHQs = explode('<div class="price-box">', $datasite);
            $pegarelemento = explode('<p class="new-price">', $elementoHQs[$i]);
			
			$pegarelemento = explode('<span class="price"', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->price = explode('</span>', $pegarelemento[1]);
                $this->price = explode("R$",$this->price[0])[1];
            }else{
				
            }
			
			$elementoHQs = explode('<div class="price-box">', $datasite);
            $pegarelemento = explode('<p class="new-price">', $elementoHQs[$i]);
			
			$pegarelemento = explode('Preço Promocional</span>', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->priceespecial = explode('</span>', $pegarelemento[1])[0];
            }else{
				$this->priceespecial = "";
            }
			
			$elementoHQs = explode('<div class="sp"></div>', $datasite);
            $pegarelemento = explode('<div class="short-description">', $elementoHQs[$i]);
			
			$pegarelemento = explode('class="product-image"><img src="', $pegarelemento[0]);
            if(isset($pegarelemento[1])) {
                $this->imagem = explode('" width="135" height="135"', $pegarelemento[1]);
                $this->imagem = $this->imagem[0];
            }else{
                $this->imagem = "";
            }

            $this->Formatter();
            
            $saraivabook = ['titulo'=> $this->titulo,
                            'preco' => $this->price,
                            'link'=>strval($this->link).$this->url_affiliate,
							'priceespecial'=>$this->priceespecial,
                            'imagem'=>$this->imagem];
            if(isset($this->titulo)) {
                array_push($bodysearch, $saraivabook);
            }
        }

        return $bodysearch;
    }

    private function Formatter(){
        //Tratando dado Preço
        $this->price = str_replace("R$", "", $this->price);
        $this->price = str_replace("\\n", "", $this->price);
        $this->price = str_replace(">\n ", "", $this->price);
        $this->price = str_replace("\n ", "", $this->price);
        $this->price = str_replace(" ", "", $this->price);
        $this->price = str_replace(",", ".", $this->price);

        //Tratando dado Preço Especial
        if($this->priceespecial !=""){
            $this->priceespecial = explode('R$', $this->priceespecial)[1];
            $this->priceespecial = str_replace("R$", "", $this->priceespecial);
            $this->priceespecial = str_replace("\\n", "", $this->priceespecial);
            $this->priceespecial = str_replace(">\n ", "", $this->priceespecial);
            $this->priceespecial = str_replace("\n ", "", $this->priceespecial);
            $this->priceespecial = str_replace(" ", "", $this->priceespecial);
            $this->priceespecial = str_replace(",", ".", $this->priceespecial);
        }
    }
}
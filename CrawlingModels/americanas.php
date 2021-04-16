<?php

namespace App\model\CrawlingModel;

class americanas
{
    //Variavies de retorno
    private $titulo;
    private $price;
    private $link;
    private $desconto;
    private $url_affiliate;
    private $proxyLink;

    function __construct(){
        $this->url_affiliate = '?opn=AFLACOM&epar=b2wafiliados&franq=AFL-03-5925651';
        $this->proxyLink="http://api.scraperapi.com/?api_key=36f1abce3e4e0c3721006f091e1e627a&url=";
    }

    public function SimpleBookSeach($term,$page){
        return $this->SimpleBookSeach_($term,$page);
    }

    public function GetBook_Simple($isbn){
        return $this->GetBook_Simple_($isbn);
    }

    public function GetBook_Simple2($isbn){
        return $this->GetBook_SimpleDesconto($isbn);
    }

    private function SimpleBookSeach_($termpesquisa,$page){

        $url = $this->proxyLink."https://www.americanas.com.br/busca/".$termpesquisa."?sortBy=lowerPrice";

        if(empty($url)){
            return [''];
        }

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

        for ($i = 1; $i <= 1; $i++) {
           
            $pegarelemento = explode('<span class="src__Text-sc-154pg0p-0 src__Name-sc-1k0ejj6-3 dSRUrl">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->titulo = explode('</span><div size="13"', $pegarelemento[$i]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }
			$pegarelemento = explode('src__PromotionalPrice-sc-1k0ejj6-7 iIPzUu">R$ <!-- -->', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->price = explode('</span><span class="src_', $pegarelemento[$i]);
                $this->price = substr($this->price[0],0,5);
            }else{
                $this->price = "";
            }
			$pegarelemento = explode('<div class="src__Wrapper-sc-1k0ejj6-2 dGIFSc"><a to="', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->link= explode('" config="[object Object]"', $pegarelemento[$i]);
                $this->link= "https://www.americanas.com.br".$this->link[0];
            }else{
                $this->link = "";
            }
            $saraivabook = ['titulo'=> $this->titulo,
                            'preco' => $this->price,
                            'link'=>strval($this->link).$this->url_affiliate];
           array_push($bodysearch, $saraivabook);
        }
        return $bodysearch;
    }
    private function GetBook_Simple_($termpesquisa){

        $url = $this->proxyLink.'https://www.americanas.com.br/busca/'.$termpesquisa.'?chave_search=achistory&sortBy=lowerPrice';

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
            return $saraivabook = ['preco' => "0.00",
                            'link'=>""];
        }

		$pegarelemento = explode('"salesPrice":', $datasite);
         if(isset($pegarelemento[1])) {
             $this->price = explode(',"sku":"', $pegarelemento[1]);
             $this->price = $this->price[0];
         }else{
             $this->price = "";
         }
         
		$pegarelemento = explode('<div class="src__Wrapper-sc-1k0ejj6-2 dGIFSc"><a to="', $datasite);
         if(isset($pegarelemento[1])) {
             $this->link= explode('" config="[object Object]"', $pegarelemento[1]);
             $this->link= "https://www.americanas.com.br".$this->link[0];
         }else{
             $this->link = "";
         }

        if(!empty($this->price)){
            $saraivabook = ['preco' => $this->price,
                            'link'=>strval($this->link).$this->url_affiliate];
            return $saraivabook;
        }else{
            return $saraivabook = ['preco' => "0.00",
                            'link'=>""];
        }
    }
    private function GetBook_SimpleDesconto($termpesquisa){

        $url = $this->proxyLink.'https://www.americanas.com.br/busca/'.$termpesquisa.'?chave_search=achistory&sortBy=lowerPrice';

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
            return $saraivabook = ['preco' => "0.00",
                            'link'=>""];
        }

		$pegarelemento = explode('"salesPrice":', $datasite);
         if(isset($pegarelemento[1])) {
             $this->price = explode(',"sku":"', $pegarelemento[1]);
             $this->price = substr($this->price[0],0,5);
         }else{
             $this->price = "";
         }
         
		$pegarelemento = explode('<div class="src__Wrapper-sc-1k0ejj6-2 dGIFSc"><a to="', $datasite);
         if(isset($pegarelemento[1])) {
             $this->link= explode('" config="[object Object]"', $pegarelemento[1]);
             $this->link= "https://www.americanas.com.br".$this->link[0];
         }else{
             $this->link = "";
         }

         $pegarelemento = explode('<span class="src__Text-qe8vir-2 jLsOYH">', $datasite);
         if(isset($pegarelemento[1])) {
             $this->desconto= explode('<!-- -->%</span>', $pegarelemento[1])[0];
             $this->desconto = substr($this->desconto,0,2);
         }else{
             $this->desconto = "";
         }

        if(!empty($this->price)){
            $saraivabook = ['preco' => $this->price,
                            'desconto'=>$this->desconto,
                            'link'=>strval($this->link).$this->url_affiliate];
            return $saraivabook;
        }else{
            return $saraivabook = ['preco' => "0.00",
                                   'desconto'=>"",
                                   'link'=>""];
        }
    }
}

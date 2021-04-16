<?php

namespace App\model\CrawlingModel;

class amazon
{
    //Variavies de retorno
    private $titulo;
    private $autor;
    private $link;
    private $price;
    private $price_capaDura;
    private $price_capaFlex;
    private $price_Kindle;
    private $imagem;
    private $rating;
    private $qyavaliantion;
    private $url_affiliate;
    private $ulrProxy;

    function __construct(){
        $this->url_affiliate = "&linkCode=sl2&tag=123690820-20&linkId=ec86e0a27be69fcd492f90620e77b54d&language=pt_BR&ref_=as_li_ss_tl";
        $this->ulrProxy="http://api.scraperapi.com/?api_key=36f1abce3e4e0c3721006f091e1e627a&url=";
    }

    //Variaveis do Metodo GetBook
    private $isbn10;
    private $isbn13;
    private $resumo;
    private $autorlink;
    private $paginas;
    private $genero;
    private $anopublish;
    private $desconto;

    public function Search_term($term){
        return $this->Crawling_internal($term);
    }
    public function Amazon_BookSearch($term,$pageS,$quantityS){
        if($term == "livros promoção"){
           return $this->ofertasAmazon($pageS,$quantityS);
        }
        return $this->Amazon_GetBookSearch($term,$pageS,$quantityS);
    }
    public function Get_book($ISBN){
        return $this->Crawling_Getbook($ISBN);
    }
    public function Get_bookSimple($ISBN){
        return $this->Crawling_Getbook_Goodreads($ISBN);
    }
    public function Get_bookSimpleDesconto($ISBN){
        return $this->Crawling_Getbook_Goodreads2($ISBN);
    }

    //Metodos Privados
    private function Crawling_internal($term_internal){

        $term_internal = $this->Format_url($term_internal);
        $url = $this->ulrProxy.'https://www.amazon.com.br/s?k='.$term_internal.'&i=stripbooks&__mk_pt_BR=ÅMÅŽÕÑ&ref=nb_sb_noss';
        
        $datasite = file_get_contents($url);

        $bodysearch = array();

        for ($i = 1; $i <= 10; $i++) {
            $pegarelemento = explode('<span class="a-size-medium a-color-base a-text-normal" dir="auto">', $datasite);
            if(isset($pegarelemento[$i])){
                $this->titulo = explode('</span>', $pegarelemento[$i]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }

            $pegarelemento = explode('<div class="sg-col-4-of-12 sg-col-8-of-16 sg-col-12-of-32 sg-col-12-of-20 sg-col-12-of-36 sg-col sg-col-12-of-24 sg-col-12-of-28"><div', $datasite);
            if(isset($pegarelemento[$i])){
                $pegarelemento1 = explode('<div class="sg-col-4-of-12 sg-col-8-of-28 sg-col-4-of-16 sg-col-8-of-32 sg-col sg-col-8-of-20 sg-col-8-of-36 sg-col-8-of-24">', $pegarelemento[$i]);
            }else{
                break;
            }
            //Coletando Preço Kindle, Capa Dura, Capa Flex e Comum
            //Capa Kindle
            $pegarelemento = explode('Kindle',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_Kindle = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_Kindle= ' 0,00 ';
                }
            }else{
                $this->price_Kindle = ' 0,00 ';
            }

            //Capa Flex
            $pegarelemento = explode('Capa flexível',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_capaFlex = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_capaFlex = ' 0,00 ';
                }
            }else{
                $this->price_capaFlex = ' 0,00 ';
            }

            //Capa dura
            $pegarelemento = explode('Capa dura',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_capaDura = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_capaDura = ' 0,00 ';
                }
            }else{
                $this->price_capaDura = ' 0,00 ';
            }

            //Capa Comum
            $pegarelemento = explode('omum',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('</span><span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode('data-a-color="base"><span class="a-offscreen">',$tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price = ' 0,00 ';
                }
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<a class="a-link-normal a-text-normal" href="', $datasite);
            $this->link = explode('">', $pegarelemento[$i]);

            $pegarelemento = explode('por </span><span class="a-size-base" dir="auto">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->autor = explode('</span><span', $pegarelemento[$i]);
            }else{
                $this->autor = array();
            }

            $pegarelemento = explode('srcset="', $datasite);
            $this->imagem = explode('1x, https', $pegarelemento[$i]);
            if(strlen($this->imagem[0]) > 200){
                $this->imagem = ["",""];
            }

            $pegarelemento = explode('aok-align-bottom"><span class="a-icon-alt">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->rating = explode(' de 5 estrelas</span></i><i class="a-icon a-icon-popover"></i></a>', $pegarelemento[$i]);
            }else{
                $this->rating ="";
            }

            $this->Formatter();

            $pegarelemento = explode('/dp/', $this->link[0]);
            if(isset($pegarelemento[1])){
                $this->isbn10 = explode('/ref', $pegarelemento[1]);
            }else{
                $this->isbn10 = ['',''];
            }

            $saraivabook= ['titulo'=>$this->titulo,
                           'price'=> ['Capa Comum'=>$this->price,
                                      'Capa Flexível'=>$this->price_capaFlex,
                                      'Capa Dura'=> $this->price_capaDura,
                                      'Kindle'=> $this->price_Kindle],
                           'link'=> "https://www.amazon.com.br" . $this->link[0].$this->url_affiliate,
                           'autor'=> strval($this->autor),
                           'imagem'=> strval($this->imagem[0]),
                            'url'=>$url,
                            'isbn'=>$this->isbn10[0],
                           'rating'=> array("score" => floatval($this->rating))];
            array_push($bodysearch, $saraivabook);
            $saraivabook = null;
        }
        return $bodysearch;
    }
    private function Crawling_Getbook($ISBN10){

        $url = $this->ulrProxy.'https://www.amazon.com.br/dp/'.$ISBN10;

        $datasite = file_get_contents($url);

        $bodysearch = array();

        $pegarelemento= explode('<span id="productTitle" class="a-size-extra-large">',$datasite);
        $this->titulo = explode('</span>',$pegarelemento[1]);

        $pegarelemento= explode('<noscript>',$datasite);
        $this->resumo = explode('<em></em>',$pegarelemento[2]);

        $pegarelemento= explode('ISBN-10',$datasite);
        $this->isbn10 = explode('</span></li>',$pegarelemento[1]);

        $pegarelemento= explode("ISBN-13",$datasite);
        $this->isbn13 = explode('</span></li>',$pegarelemento[1]);

        $pegarelemento= explode('<span class="a-text-bold">Capa comum',$datasite);
        $this->paginas = explode('páginas</span>',$pegarelemento[1]);

        $pegarelemento= explode('</span><span class="a-size-small"> por ',$datasite);
        $this->autor = explode('</span>',$pegarelemento[1]);

        $pegarelemento= explode('link rel="canonical" href="',$datasite);
        $this->link = explode('" />',$pegarelemento[1]);

        $pegarelemento= explode('id="imgBlkFront" data-a-dynamic-image="{&quot;',$datasite);
        $this->imagem = explode('&quot;:[',$pegarelemento[1]);

        $pegarelemento= explode('<i class="a-icon a-icon-star a-star-5"><span class="a-icon-alt">',$datasite);
        $this->rating = explode(' de 5 estrelas</span>',$pegarelemento[1]);

        $pegarelemento= explode('<span id="acrCustomerReviewText" class="a-size-base">',$datasite);
        $this->qyavaliantion = explode(' classificações</span>',$pegarelemento[1]);

        $genero1 = array();
        $pegarelemento= explode('<a class="a-link-normal a-color-tertiary"',$datasite);
        for($i=1;$i<4;$i++){
            if(isset($pegarelemento[$i])) {
                $temp = explode('</a>', $pegarelemento[$i]);
                if ($temp[0] != "" || $temp[0] != null) {
                    array_push($genero1, $temp[0]);
                }
            }
        }
        $this->genero = $genero1;

        $pegarelemento= explode('<span class="a-text-bold">Editora',$datasite);
        $this->anopublish = explode('</span></li>',$pegarelemento[1]);

        $this->Formatter_getbook();

        $goodreads =['titulo'=>strval($this->titulo),
                    'autor' => array(["nome"=>strval($this->autor[0])]),

                    'livro' => array(["isbn13"=>strval($this->isbn13),"isbn10"=>$this->isbn10,
                    "resumo"=> strval($this->resumo),"paginas"=>strval($this->paginas),
                    "generos"=>array([$this->genero]),"publicacao"=>$this->anopublish]),

                    "link" => $this->link[0].$this->url_affiliate,"image"=> strval($this->imagem[0]),

                    "rating" => array(["score"=>floatval($this->rating),
                        "reviews"=>intval($this->qyavaliantion)])];

        if(!$this->titulo == null || !$this->titulo == "" || !isset($gthis->titulo)){
            array_push($bodysearch,$goodreads);
        }

        return $bodysearch;
    }
    private function Crawling_Getbook_Goodreads($ISBN10){

        if(empty($ISBN10)){
            return [''];
        }

        $url = $this->ulrProxy.'https://www.amazon.com.br/dp/'.$ISBN10;
        
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

        //Pegando Link
        $pegarelemento= explode('link rel="canonical" href="',$datasite);
        if(isset($pegarelemento[1])){
            $this->link = explode('" />',$pegarelemento[1]);
        }else{
            return [""];
        }

        //Pegando o preço
        $pegarelemento= explode('<ul class="a-unordered-list a-nostyle a-button-list a-horizontal">',$datasite);
        $pegarelemento2 = explode('<div id="promoPriceBlockMessage_feature_div" class="celwidget" data-feature-name="promoPriceBlockMessage">',$pegarelemento[1]);

        //Pegando Preço Kindle
        $pegarelemento= explode('ndle</span> <br/>',$pegarelemento2[0]);
        if(isset($pegarelemento[1])){
        $this->price_Kindle=explode('</span>',$pegarelemento[1]);
        }else{
            $this->price_Kindle = ['0','0'];
        }

        //Pegando Preço Capa Dura
        $pegarelemento= explode('ura</span> <br/>',$pegarelemento2[0]);
        if(isset($pegarelemento[1])){
        $this->price_capaDura=explode('</span>',$pegarelemento[1]);
        }else{
            $this->price_capaDura = ['0','0'];
        }

        //Pegando Preço Outras
        $pegarelemento= explode('omum</span> <br/>',$pegarelemento2[0]);
        if(isset($pegarelemento[1])){
        $this->price_capaFlex=explode('</span>',$pegarelemento[1]);
        }else{
            $this->price_capaFlex =  ['0','0'];
        }

        //Pgando Avaliação
        $pegarelemento= explode('-5"><span class="a-icon-alt">',$datasite);
        if(isset($pegarelemento[1])){
            $this->rating = explode(' de 5 estrelas</span>',$pegarelemento[1]);
        }else{
            $this->rating = ['',''];
        }
        

        //Pegando quantidade de avaliações
        $pegarelemento= explode('<span id="acrCustomerReviewText" class="a-size-base">',$datasite);
        if(isset($pegarelemento[1])){
            $this->qyavaliantion = explode(' classificações</span>',$pegarelemento[1]);
        }else{
            $this->qyavaliantion = ['',''];
        }


        $this->Formatter_goodreads();

        $AmazonTogoodreads =["link" => $this->link[0].$this->url_affiliate,
                     "price" => array("kindle"=>$this->price_Kindle,
                                      "capadura"=>$this->price_capaDura,
                                      "capacomum"=>$this->price_capaFlex),
                     "score"=>floatval($this->rating),
                     "reviews"=>$this->qyavaliantion];

        if(!$this->titulo == null || !$this->titulo == "" || !isset($gthis->titulo)){
            array_push($bodysearch,$AmazonTogoodreads);
        }

        return $bodysearch;
    }
    private function Crawling_Getbook_Goodreads2($ISBN10){

        if(empty($ISBN10)){
            return [''];
        }

        $url = $this->ulrProxy.'https://www.amazon.com.br/dp/'.$ISBN10;
        
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

        //Pegando Link
        $pegarelemento= explode('link rel="canonical" href="',$datasite);
        if(isset($pegarelemento[1])){
            $this->link = explode('" />',$pegarelemento[1]);
        }else{
            return [""];
        }

        //Pegando o preço
        $pegarelemento= explode('<ul class="a-unordered-list a-nostyle a-button-list a-horizontal">',$datasite);
        $pegarelemento2 = explode('<div id="promoPriceBlockMessage_feature_div" class="celwidget" data-feature-name="promoPriceBlockMessage">',$pegarelemento[1]);

        //Pegando Preço Kindle
        $pegarelemento= explode('ndle</span> <br>',$pegarelemento2[0]);
        if(isset($pegarelemento[1])){
        $this->price_Kindle=explode('</span>',$pegarelemento[1]);
        }else{
            $this->price_Kindle = ['0','0'];
        }

        //Pegando Preço Capa Dura
        $pegarelemento= explode('ura</span> <br>',$pegarelemento2[0]);
        if(isset($pegarelemento[1])){
        $this->price_capaDura=explode('</span>',$pegarelemento[1]);
        }else{
            $this->price_capaDura = ['0','0'];
        }

        //Pegando Preço Outras
        $pegarelemento= explode('omum</span> <br>',$pegarelemento2[0]);
        if(isset($pegarelemento[1])){
        $this->price_capaFlex=explode('</span>',$pegarelemento[1]);
        }else{
            $this->price_capaFlex =  ['0','0'];
        }

        //Pegando Avaliação
        $pegarelemento= explode('-5"><span class="a-icon-alt">',$datasite);
        if(isset($pegarelemento[1])){
            $this->rating = explode(' de 5 estrelas</span>',$pegarelemento[1]);
        }else{
            $this->rating = ['',''];
        }
        
        //Pegando Desconto
        $pegarelemento= explode('Você economiza:',$datasite);
        if(isset($pegarelemento[1])){
            $this->desconto = explode('</span>',$pegarelemento[1]);
            $this->desconto = explode('(',$this->desconto[0])[1];
            $this->desconto  =str_replace("\n", "",$this->desconto);
            $this->desconto  =str_replace(")", "",$this->desconto);
            $this->desconto  =str_replace("%", "",$this->desconto);
        }else{
            $this->desconto = "";
        }

        //Pegando quantidade de avaliações
        $pegarelemento= explode('<span id="acrCustomerReviewText" class="a-size-base">',$datasite);
        $this->qyavaliantion = explode(' classificações</span>',$pegarelemento[1]);


        $this->Formatter_goodreads();

        $AmazonTogoodreads =["link" => $this->link[0].$this->url_affiliate,
                     "price" => array("kindle"=>$this->price_Kindle,
                                      "capadura"=>$this->price_capaDura,
                                      "capacomum"=>$this->price_capaFlex),
                     "score"=>floatval($this->rating),
                     "desconto"=>$this->desconto,
                     "reviews"=>$this->qyavaliantion];

        if(!$this->titulo == null || !$this->titulo == "" || !isset($gthis->titulo)){
            array_push($bodysearch,$AmazonTogoodreads);
        }

        return $bodysearch;
    }
    private function ISBN($link_){
        $url = $this->ulrProxy.''.$link_;
        $datasite = file_get_contents($url);

        $pegarelemento = explode('"productAsin":"', $datasite);
        $isbnAchado = explode('"productMerchantID":', $pegarelemento[0]);

        if(!isset($isbnAchado)){
            $isbnAchado = null;
        }   
        return $isbnAchado;
    }
    private function Amazon_GetBookSearch($term_internal,$page_,$booksQuantity_){

        $term_internal = $this->Format_url($term_internal);
        if($page_ >1){
            $url = $this->ulrProxy.'https://www.amazon.com.br/s?k='.$term_internal.'&i=stripbooks&__mk_pt_BR=ÅMÅŽÕÑ&ref=nb_sb_noss&page='.$page_;
        }else{
            $url = $this->ulrProxy.'https://www.amazon.com.br/s?k='.$term_internal.'&i=stripbooks&__mk_pt_BR=ÅMÅŽÕÑ&ref=nb_sb_noss_2'; 
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
        }catch (Exception $e){
            return [''];
        }

        $bodysearch = array();

            $pegarelemento = explode('<span dir="auto">', $datasite);
            if(isset($pegarelemento[1])){
                $booksQuantity2_ = explode('</span>', $pegarelemento[1]);
                $booksQuantity2_ = $booksQuantity2_;
            }else{
                $booksQuantity2_ = null;
            }

        if(empty($booksQuantity2_)){
            return [""];
        }else if($booksQuantity2_ < $booksQuantity_){
            $booksQuantity_ =$booksQuantity2_;
        }

        for ($i = 1; $i <= $booksQuantity_; $i++) {
            $pegarelemento = explode('<span class="a-size-medium a-color-base a-text-normal" dir="auto">', $datasite);
            if(isset($pegarelemento[$i])){
                $this->titulo = explode('</span>', $pegarelemento[$i]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }

            $pegarelemento = explode('div class="a-section a-spacing-none a-spacing-top-small">', $datasite);
            if(isset($pegarelemento[$i])){
                $pegarelemento1 = explode('<div class="sg-col-4-of-12 sg-col-4-of-16 sg-col sg-col-8-of-20"><div class="sg-col-inner">', $pegarelemento[$i]);
            }else{
                break;
            }
            //Coletando Preço Kindle, Capa Dura, Capa Flex e Comum
            //Capa Kindle
            $pegarelemento = explode('Kindle',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_Kindle = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_Kindle= ' 0,00 ';
                }
            }else{
                $this->price_Kindle = ' 0,00 ';
            }

            //Capa Flex
            $pegarelemento = explode('Capa flexível',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_capaFlex = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_capaFlex = ' 0,00 ';
                }
            }else{
                $this->price_capaFlex = ' 0,00 ';
            }

            //Capa dura
            $pegarelemento = explode('Capa dura',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_capaDura = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_capaDura = ' 0,00 ';
                }
            }else{
                $this->price_capaDura = ' 0,00 ';
            }

            //Capa Comum
            $pegarelemento = explode('omum',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('</span><span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode('data-a-color="base"><span class="a-offscreen">',$tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price = ' 0,00 ';
                }
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<a class="a-link-normal a-text-normal" href="', $datasite);
            $this->link = explode('">', $pegarelemento[$i]);

            $pegarelemento = explode('por </span><span class="a-size-base" dir="auto">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->autor = explode('</span><span', $pegarelemento[$i]);
            }else{
                $this->autor = array();
            }

            $pegarelemento = explode('srcset="', $datasite);
            $this->imagem = explode('1x, https', $pegarelemento[$i]);
            if(strlen($this->imagem[0]) > 200){
                $this->imagem = ["",""];
            }

            $this->Formatter();

            $pegarelemento = explode('/dp/', $this->link[0]);
            if(isset($pegarelemento[1])){
                $this->isbn10 = explode('/ref', $pegarelemento[1]);
            }else{
                $this->isbn10 = ['',''];
            }

            $saraivabook= ['titulo'=>$this->titulo,
                           'price'=> ['Capa Comum'=>$this->price,
                                      'Capa Flexível'=>$this->price_capaFlex,
                                      'Capa Dura'=> $this->price_capaDura,
                                      'Kindle'=> $this->price_Kindle],
                           'link'=> "https://www.amazon.com.br" . $this->link[0].$this->url_affiliate,
                           'autor'=> strval($this->autor),
                           'imagem'=> strval($this->imagem[0]),
                            'url'=>$url,
                            'isbn'=>$this->isbn10[0]];
            array_push($bodysearch, $saraivabook);
            $saraivabook = null;
        }
        return $bodysearch;
    }

    //Metodos Privados de formatação do Crawler
    private function Formatter(){
        //Tratando dado Preço
        $this->price = str_replace("R$", "", $this->price[0]);
        $this->price = str_replace("\\n", "", $this->price);
        $this->price = str_replace(">\n ", "", $this->price);
        $this->price = str_replace("\n ", "", $this->price);
        $this->price = str_replace(" ", "", $this->price);
        $this->price = str_replace(",", ".", $this->price);
        $this->price = $this->validadorPreco($this->price,8,'0.00');

        //Tratando dado Preço Kindle
        $this->price_Kindle = str_replace("R$", "", $this->price_Kindle[0]);
        $this->price_Kindle = str_replace("\\n", "", $this->price_Kindle);
        $this->price_Kindle  = str_replace(">\n ", "", $this->price_Kindle);
        $this->price_Kindle  = str_replace("\n ", "", $this->price_Kindle );
        $this->price_Kindle  = str_replace(" ", "", $this->price_Kindle );
        $this->price_Kindle  = str_replace(",", ".", $this->price_Kindle );
        $this->price_Kindle = $this->validadorPreco($this->price_Kindle,8,"0.00");

        //Tratando dado Preço Capa Dura
        $this->price_capaDura= str_replace("R$", "", $this->price_capaDura[0]);
        $this->price_capaDura = str_replace("\\n", "", $this->price_capaDura);
        $this->price_capaDura  = str_replace(">\n ", "", $this->price_capaDura);
        $this->price_capaDura  = str_replace("\n ", "", $this->price_capaDura);
        $this->price_capaDura  = str_replace(" ", "", $this->price_capaDura);
        $this->price_capaDura  = str_replace(",", ".", $this->price_capaDura);
        $this->price_capaDura  = $this->validadorPreco($this->price_capaDura,8,"0.00");

        //Tratando dado Preço
        $this->price_capaFlex = str_replace("R$", "", $this->price_capaFlex[0]);
        $this->price_capaFlex = str_replace("\\n", "", $this->price_capaFlex);
        $this->price_capaFlex  = str_replace(">\n ", "", $this->price_capaFlex);
        $this->price_capaFlex  = str_replace("\n ", "", $this->price_capaFlex);
        $this->price_capaFlex  = str_replace(" ", "", $this->price_capaFlex);
        $this->price_capaFlex  = str_replace(",", ".", $this->price_capaFlex);
        $this->price_capaFlex  = $this->validadorPreco($this->price_capaFlex ,8,"0.00");

        //Trando dado Autor para string sem caracteres adicionais
        if(isset($this->autor[0])) {
            $this->autor = str_replace("\n", "", $this->autor[0]);
        }else{
            $this->autor ="";
        }

        //Trando link de imagem para formato de exbição ajustado
        if(isset($this->imagem[0])) {
            $this->imagem[0] = str_replace("\/", "/", $this->imagem[0]);
            $this->imagem[0] = str_replace("-400-400", "", $this->imagem[0]);
        }else{
            $this->imagem ="";
        }

        //Trando dado rating e Reviews
        if(isset($this->rating[0])) {
            $this->rating = str_replace(",", ".", $this->rating[0]);
        }else{
            $this->rating = "";
        }
    }
    private function Formatter_getbook(){
        //Tratando isbn13
        $this->isbn13 =str_replace("\n", "",$this->isbn13[0]);
        $this->isbn13 =str_replace("<span>", "",$this->isbn13);
        $this->isbn13 =str_replace("</span>", "",$this->isbn13);
        $this->isbn13 =str_replace(":", "",$this->isbn13);
        $this->isbn13 =str_replace("-", "",$this->isbn13);

        //Tratando isbn10
        $this->isbn10=str_replace("\n", "",$this->isbn10[0]);
        $this->isbn10=str_replace(" ", "",$this->isbn10);
        $this->isbn10 =str_replace("<span>", "",$this->isbn10);
        $this->isbn10 =str_replace("</span>", "",$this->isbn10);
        $this->isbn10 =str_replace(":", "",$this->isbn10);

        //Tratando titulo
        $this->titulo=str_replace("\n", "",$this->titulo[0]);

        //Tratando resumo
        $this->resumo  =str_replace("\n", "",$this->resumo[0]);
        $this->resumo =str_replace("<em>", "",$this->resumo);
        $this->resumo =str_replace("<strong>", "",$this->resumo);
        $this->resumo =str_replace("<p>", "",$this->resumo);
        $this->resumo =str_replace("</p>", "",$this->resumo);
        $this->resumo =str_replace("</div>", "",$this->resumo);
        $this->resumo =str_replace("<div>", "",$this->resumo);

        //Tratando score
        $this->rating  =str_replace("\n", "",$this->rating[0]);
        $this->rating  =str_replace(",", ".",$this->rating);
        $this->rating  =str_replace(",", "",$this->rating);
        $this->rating  =floatval($this->rating);

        //Tratando Paginas
        $this->paginas=str_replace("\n", "",$this->paginas[0]);
        $this->paginas=str_replace("<div>", "",$this->paginas);
        $this->paginas=str_replace("</div>", "",$this->paginas);
        $this->paginas=str_replace(":", "",$this->paginas);
        $this->paginas=str_replace("<span>", "",$this->paginas);
        $this->paginas =str_replace("</span>", "",$this->paginas);

        //Tratando Reviews
        $this->qyavaliantion=str_replace(".", "",$this->qyavaliantion[0]);

        //Tratando Ano Publish
        $this->anopublish =str_replace("\n", "",$this->anopublish[0]);
        $this->anopublish =str_replace(" ", "",$this->anopublish);
        $this->anopublish =str_replace("<span>", "",$this->anopublish);
        $this->anopublish =str_replace("</span>", "",$this->anopublish);
        $this->anopublish =str_replace(":", "",$this->anopublish);

        //Tratando genero
        $genero1 = array();
        for($i = 1; $i < count($this->genero);$i++) {
            $strtemp =str_replace("\n", "",$this->genero[$i]);
            $strtemp =str_replace('"', "",$strtemp);
            $strtemp =substr($strtemp,67,1000);
            $strtemp =substr($strtemp,0,-12);
            array_push($genero1,$strtemp);
        }
        $this->genero = $genero1;
    }
    private function Formatter_goodreads(){

        //Tratando score
        $this->rating  =str_replace("\n", "",$this->rating[0]);
        $this->rating  =str_replace(",", ".",$this->rating);
        $this->rating  =str_replace(",", "",$this->rating);
        $this->rating  =$this->rating;

        //Tratando reviews
        $this->qyavaliantion =str_replace("\n", "",$this->qyavaliantion[0]);
        $this->qyavaliantion =strip_tags($this->qyavaliantion);
        $this->qyavaliantion = floatval($this->qyavaliantion);

         //Tratando dado Preço Kindle
         $this->price_Kindle = str_replace("R$", "", $this->price_Kindle[0]);
         $this->price_Kindle  = strip_tags($this->price_Kindle);
         $this->price_Kindle  = str_replace("\n", "", $this->price_Kindle );
         $this->price_Kindle  = str_replace(" ", "", $this->price_Kindle );
         $this->price_Kindle  = str_replace(",", ".", $this->price_Kindle );
         $this->price_Kindle  = $this->validadorPreco($this->price_Kindle,8,"Grátis");
 
         //Tratando dado Preço Capa Dura
         $this->price_capaDura= str_replace("R$", "", $this->price_capaDura[0]);
         $this->price_capaDura = strip_tags($this->price_capaDura);
         $this->price_capaDura = str_replace("\\n", "", $this->price_capaDura);
         $this->price_capaDura  = str_replace(">\n ", "", $this->price_capaDura);
         $this->price_capaDura  = str_replace("\n", "", $this->price_capaDura);
         $this->price_capaDura  = str_replace(" ", "", $this->price_capaDura);
         $this->price_capaDura  = str_replace(",", ".", $this->price_capaDura);
         $this->price_capaDura  = $this->validadorPreco($this->price_capaDura,8,"0.00");

         //Tratando dado Preço
         $this->price_capaFlex = str_replace("R$", "", $this->price_capaFlex[0]);
         $this->price_capaFlex  = strip_tags($this->price_capaFlex);
         $this->price_capaFlex = str_replace("\\n", "", $this->price_capaFlex);
         $this->price_capaFlex  = str_replace(">\n ", "", $this->price_capaFlex);
         $this->price_capaFlex  = str_replace("\n", "", $this->price_capaFlex);
         $this->price_capaFlex  = str_replace(" ", "", $this->price_capaFlex);
         $this->price_capaFlex  = str_replace(",", ".", $this->price_capaFlex);
         $this->price_capaFlex  = $this->validadorPreco($this->price_capaFlex,8,"0.00");
    }
    private function Format_url($query){
        $query = $this->tirarAcentos($query);
        $dataparse = str_replace(" ", "+", $query);
        return $dataparse;
    }
    private function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }
    private function validadorPreco($string,$qtdCaracteres,$erroHipo){
        if(strlen($string)>8){
            return $erroHipo;
        }else{
            return $string;
        }
    }

    private function ofertasAmazon($page_,$booksQuantity_){

        $url = $this->ulrProxy.'https://www.amazon.com.br/s?k=livros+em+promo%C3%A7%C3%A3o&i=stripbooks&__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&ref=nb_sb_noss&page='.$page_;
        $datasite = file_get_contents($url);

        $bodysearch = array();

        if(!isset($booksQuantity_)){
            $booksQuantity_ =16;
        }else if($booksQuantity_ >15){
            $booksQuantity_ =15;
        }

        for ($i = 1; $i <= $booksQuantity_; $i++) {
            $pegarelemento = explode('<span class="a-size-medium a-color-base a-text-normal" dir="auto">', $datasite);
            if(isset($pegarelemento[$i])){
                $this->titulo = explode('</span>', $pegarelemento[$i]);
                $this->titulo = $this->titulo[0];
            }else{
                $this->titulo = "";
            }

            $pegarelemento = explode('div class="a-section a-spacing-none a-spacing-top-small">', $datasite);
            if(isset($pegarelemento[$i])){
                $pegarelemento1 = explode('<div class="sg-col-4-of-12 sg-col-4-of-16 sg-col sg-col-8-of-20"><div class="sg-col-inner">', $pegarelemento[$i]);
            }else{
                break;
            }
            //Coletando Preço Kindle, Capa Dura, Capa Flex e Comum
            //Capa Kindle
            $pegarelemento = explode('Kindle',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_Kindle = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_Kindle= ' 0,00 ';
                }
            }else{
                $this->price_Kindle = ' 0,00 ';
            }

            //Capa Flex
            $pegarelemento = explode('Capa flexível',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_capaFlex = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_capaFlex = ' 0,00 ';
                }
            }else{
                $this->price_capaFlex = ' 0,00 ';
            }

            //Capa dura
            $pegarelemento = explode('Capa dura',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('<span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode(' <span class="a-price" data-a-size="l" data-a-color="base"><span class="a-offscreen">',
                    $tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price_capaDura = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price_capaDura = ' 0,00 ';
                }
            }else{
                $this->price_capaDura = ' 0,00 ';
            }

            //Capa Comum
            $pegarelemento = explode('omum',$pegarelemento1[0]);
            if(isset($pegarelemento[1])) {
                $tratamento_price = explode('</span><span aria-hidden="true"><span class="a-price-', $pegarelemento[1]);
                $pegarelemento = explode('data-a-color="base"><span class="a-offscreen">',$tratamento_price[0]);
                if(isset($pegarelemento[1])){
                    $this->price = explode('</span>', $pegarelemento[1]);
                }else{
                    $this->price = ' 0,00 ';
                }
            }else{
                $this->price = ' 0,00 ';
            }

            $pegarelemento = explode('<a class="a-link-normal a-text-normal" href="', $datasite);
            $this->link = explode('">', $pegarelemento[$i]);

            $pegarelemento = explode('por </span><span class="a-size-base" dir="auto">', $datasite);
            if(isset($pegarelemento[$i])) {
                $this->autor = explode('</span><span', $pegarelemento[$i]);
            }else{
                $this->autor = array();
            }

            $pegarelemento = explode('srcset="', $datasite);
            $this->imagem = explode('1x, https', $pegarelemento[$i]);
            if(strlen($this->imagem[0]) > 200){
                $this->imagem = ["",""];
            }

            $this->Formatter();

            $pegarelemento = explode('/dp/', $this->link[0]);
            if(isset($pegarelemento[1])){
                $this->isbn10 = explode('/ref', $pegarelemento[1]);
            }else{
                $this->isbn10 = ['',''];
            }

            $saraivabook= ['titulo'=>$this->titulo,
                           'price'=> ['Capa Comum'=>$this->price,
                                      'Capa Flexível'=>$this->price_capaFlex,
                                      'Capa Dura'=> $this->price_capaDura,
                                      'Kindle'=> $this->price_Kindle],
                           'link'=> "https://www.amazon.com.br" . $this->link[0].$this->url_affiliate,
                           'autor'=> strval($this->autor),
                           'imagem'=> strval($this->imagem[0]),
                            'url'=>$url,
                            'isbn'=>$this->isbn10[0]];
            array_push($bodysearch, $saraivabook);
            $saraivabook = null;
        }
        return $bodysearch;
    }
}

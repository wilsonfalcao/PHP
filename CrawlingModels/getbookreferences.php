<?php

namespace App\model\CrawlingModel;

class getbookreferences
{
    //Variavies de retorno
    private $titulo;
    private $autor;
    private $link;
    private $resumo;
    private $autorlink;
    private $isbn10;
    private $isbn13;
    private $paginas;
    private $imagem_autor;
    private $imagem_livro;
    private $rating;
    private $qyavaliantion;
    private $genero;
    private $anopublish;
    private $nascimento;
    private $linguagem;
    private $tipocapa;

    public function Search_term($term){
        return $this->Crawling_internal($term);
    }
    public function GetAuthor_data($urlterm){
        return $this->AboutAutor($urlterm);
    }
    public function Getfullbook($ISBN_){
        return $this->Getfullbook_($ISBN_);
    }
    public function GetbookPrices($ISBN10_,$ISBN13_,$DESCONTO){
        return $this->GetbookPrices_($ISBN10_,$ISBN13_,$DESCONTO);
    }

    private function AboutAutor($siteAutor){

        $url = "https://www.goodreads.com/author/show/".$siteAutor;

        $datasite = file_get_contents($url);

        $bodysearch = array();

        $pegarelemento= explode('<div class="leftContainer authorLeftContainer">
      <a title="',$datasite);
        $autor = explode('" rel="',$pegarelemento[1]);
        $this->autor = $autor[0];

        $pegarelemento= explode('<a target="_blank" rel="noopener noreferrer" itemprop="url" href="',$datasite);
        $website = explode('">',$pegarelemento[1]);
        $this->autorlink = $website[0];

        $pegarelemento= explode("itemprop='birthDate'>",$datasite);
        $nascimento = explode("</div>",$pegarelemento[1]);
        $this->nascimento = $nascimento[0];

        $pegarelemento= explode('<meta itemprop="image" content="',$datasite);
        if(isset($pegarelemento[1])){
            $imagem= explode('">',$pegarelemento[1]);
            $this->imagem_autor = $imagem[0];
        }else{
            $this->imagem_autor = "";
        }

        $pegarelemento= explode('<span id="freeTextContainerauthor',$datasite);
        $this->resumo= explode('<br/><br/></span>',$pegarelemento[1]);

        $this->FomatterLivroAutor();

        $aboutAutor = ["autor"=>$this->autor,
                        "site"=> $this->autorlink,
                        "nascimento"=>$this->nascimento,
                        "imagem"=> $this->imagem_autor,
                        "about"=>$this->resumo,
                        "URLX"=>$url];

        for($i=1;$i <=10;$i++){
            $pegarelemento= explode("<span itemprop='name' role='heading' aria-level='4'>",$datasite);
            $this->titulo = explode('</span>',$pegarelemento[$i]);

            $pegarelemento= explode('<a class="bookTitle" itemprop="url" href="',$datasite);
            $this->link = explode('">',$pegarelemento[$i]);

            $pegarelemento= explode('class="bookCover" itemprop="image" src="',$datasite);
            $this->imagem_livro = explode('" />',$pegarelemento[$i]);

            $pegarelemento= explode('class="staticStar p6"></span><span size="12x12" class="staticStar p0"></span></span> ',$datasite);
            if(isset($pegarelemento[$i])) {
                $this->rating = explode(' avg rating &mdash;', $pegarelemento[$i]);
            }else{
                $this->rating ="";
            }

            $pegarelemento= explode(' avg rating &mdash; ',$datasite);
            $this->reviews = explode(' ratings</span>',$pegarelemento[$i]);

            $this->FomatterAboutAutor();

            $goodreads = ["livro"=> $this->titulo[0],
                         "link"=>$this->link,
                         "imagem"=>strval($this->imagem_livro[0]),
                         "rating"=>intval($this->rating),
                         "reviews"=>intval($this->reviews[0])];

            array_push($bodysearch, $goodreads );
            $goodreads = null;
        }
        $tempData = ["autor"=>$aboutAutor,
                    "livros"=>$bodysearch];
        return $tempData;
    }
    private function Crawling_internal($term_internal){

        $url = "https://www.goodreads.com/book/isbn/".$term_internal;

        $datasite = file_get_contents($url);

        $bodysearch = array();

        $pegarelemento= explode('<h1 id="bookTitle" class="gr-h1 gr-h1--serif" itemprop="name">',$datasite);
        $this->titulo = explode('</h1>',$pegarelemento[1]);

        $pegarelemento= explode('<span id="freeTextContainer',$datasite);
        $this->resumo = explode('<a data-text-id=',$pegarelemento[1]);

        $pegarelemento= explode('<a class="authorName" itemprop="url" href="',$datasite);
        $this->autorlink = explode('"><span',$pegarelemento[1]);

        $pegarelemento= explode('ISBN</div>
                <div class="infoBoxRowItem">',$datasite);
        $this->isbn10 = explode('<span class="',$pegarelemento[1]);

        $pegarelemento= explode("ISBN13: <span itemprop='isbn'>",$datasite);
        $this->isbn13 = explode('</span>',$pegarelemento[1]);

        $pegarelemento= explode('<span itemprop="numberOfPages">',$datasite);
        $this->paginas = explode('</span>',$pegarelemento[1]);

        $pegarelemento= explode("itemprop='inLanguage'>",$datasite);
        $this->linguagem = explode('</div>',$pegarelemento[1]);

        $pegarelemento= explode('<div class="row"><span itemprop="bookFormat">',$datasite);
        $this->tipocapa = explode('</span>,',$pegarelemento[1]);

        $pegarelemento= explode('"><span itemprop="name">',$datasite);
        $this->autor = explode('</span></a>',$pegarelemento[1]);

        $pegarelemento= explode('<a rel="nofollow" itemprop="image" href="',$datasite);
        $this->link = explode('"><img id=',$pegarelemento[1]);

        $pegarelemento= explode('style= "display: none;">
            <img src="',$datasite);
        $this->imagem = explode('" alt="',$pegarelemento[1]);

        $pegarelemento= explode('<span itemprop="ratingValue">',$datasite);
        $this->rating = explode('</span>',$pegarelemento[1]);

        $pegarelemento= explode('<meta itemprop="ratingCount" content="',$datasite);
        $this->qyavaliantion = explode('" />',$pegarelemento[1]);

        $this->genero = array();
        for($i=1;$i<=10;$i++){
            $pegarelemento= explode('<div class="left">
        <a class="actionLinkLite bookPageGenreLink" href="/genres/',$datasite);
            if(isset($pegarelemento[$i])) {
                $temp = explode('">', $pegarelemento[$i]);
                if ($temp[0] != "" || $temp[0] != null) {
                    array_push($this->genero, $temp[0]);
                }
            }else{
            }
        }

        $pegarelemento= explode('<div class="row">
            Published',$datasite);
        $this->anopublish = explode('</div>',$pegarelemento[1]);

        //Trantando dados
        $this->Formatter();

        $goodreads = ["autor" => array("name"=>strval($this->autor[0]),"linkautor"=>$this->autorlink[0]),
                      "isbn" => array("isbn13"=>strval($this->isbn13[0]),"isbn10"=>$this->isbn10[0]),
                      "livro" => array("titulo"=>strval($this->titulo),
                                       "linguagem"=>$this->linguagem[0],
                                       "resumo"=>strval($this->resumo),
                                       "paginas"=>strval($this->paginas),
                                       "generos"=>$this->genero,
                                       "publicacao"=>$this->anopublish,
                                       "tipocapa"=>$this->tipocapa),
                      "link" => $this->link,
                      "image" => strval($this->imagem[0]),
                      "rating"=> array("score"=>floatval($this->rating[0]),
                      "reviews"=>intval($this->qyavaliantion[0]))];

        array_push($bodysearch,$goodreads);
        return $bodysearch;
    }
    private function Getfullbook_($term_internal){

        $url = "https://www.goodreads.com/book/isbn/".$term_internal;

        $datasite = file_get_contents($url);

        $bodysearch = array();

        $pegarelemento= explode('<h1 id="bookTitle" class="gr-h1 gr-h1--serif" itemprop="name">',$datasite);
        if(isset($pegarelemento[1])){
            $this->titulo = explode('</h1>',$pegarelemento[1]);
        }else{
            $this->titulo = ['',''];
        }

        $pegarelemento= explode('<div id="descriptionContainer">',$datasite);
        if(isset($pegarelemento[1])){
            $this->resumo = explode('<a data-text-id=',$pegarelemento[1]);
        }else{
            $this->resumo = ['',''];
        }

        $pegarelemento= explode('<a class="authorName" itemprop="url" href="',$datasite);
        if(isset($pegarelemento[1])){
            $this->autorlink = explode('"><span',$pegarelemento[1]);
        }else{
            $this->autorlink  = ['',''];
        }

        $pegarelemento= explode('ISBN</div>
                <div class="infoBoxRowItem">',$datasite);
        if(isset($pegarelemento[1])){
            $this->isbn10 = explode('<span class="',$pegarelemento[1]);
        }else{
            $this->isbn10 = ['',''];
        }

        $pegarelemento= explode("<span itemprop='isbn'>",$datasite);
        if(isset($pegarelemento[1])){
            $this->isbn13 = explode('</span>',$pegarelemento[1]);
        }else{
            $this->isbn13 = ['',''];
        }

        if(empty($this->isbn10[0]) || $this->isbn10[0] == ""){
            $pegarelemento= explode("itemprop='isbn'>",$datasite);
            if(isset($pegarelemento[1])){
                $this->isbn13 = explode('</div>',$pegarelemento[1]);
            }else{
                $this->isbn13 = ['',''];
            }
        }
        

        $pegarelemento= explode('<span itemprop="numberOfPages">',$datasite);
        if(isset($pegarelemento[1])){
            $this->paginas = explode('</span>',$pegarelemento[1]);
            //Tratando paginas
            $this->paginas =str_replace(' pages', "",$this->paginas[0]);
            $this->paginas = intval($this->paginas);
        }else{
            $this->paginas = '';
        }

        $pegarelemento= explode("itemprop='inLanguage'>",$datasite);
        if(isset($pegarelemento[1])){
            $this->linguagem = explode('</div>',$pegarelemento[1]);
        }else{
            $this->linguagem = ['',''];
        }

        $pegarelemento= explode('<div class="row"><span itemprop="bookFormat">',$datasite);
        if(isset($pegarelemento[1])){
            $this->tipocapa = explode('</span>',$pegarelemento[1]);
        }else{
            $this->tipocapa = ['',''];
        }

        $pegarelemento= explode('"><span itemprop="name">',$datasite);
        if(isset($pegarelemento[1])){
            $this->autor = explode('</span></a>',$pegarelemento[1]);
        }else{
            $this->autor = ['',''];
        }

        $pegarelemento= explode('<a rel="nofollow" itemprop="image" href="',$datasite);
        if(isset($pegarelemento[1])){
            $this->link = explode('"><img id=',$pegarelemento[1]);
        }else{
            $this->link = ['',''];
        }

        $pegarelemento= explode('style= "display: none;">
            <img src="',$datasite);
        if(isset($pegarelemento[1])){
            $this->imagem = explode('" alt="',$pegarelemento[1]);
        }else{
            $this->imagem = ['',''];
        }

        $pegarelemento= explode('<span itemprop="ratingValue">',$datasite);
        if(isset($pegarelemento[1])){
            $this->rating = explode('</span>',$pegarelemento[1]);
        }else{
            $this->rating = ['',''];
        }

        $pegarelemento= explode('<meta itemprop="ratingCount" content="',$datasite);
        if(isset($pegarelemento[1])){
            $this->qyavaliantion = explode('" />',$pegarelemento[1]);
        }else{
            $this->qyavaliantion = ['',''];
        }

        $this->genero = array();
        for($i=1;$i<=10;$i++){
            $pegarelemento= explode('<div class="left">
        <a class="actionLinkLite bookPageGenreLink" href="/genres/',$datasite);
            if(isset($pegarelemento[$i])) {
                $temp = explode('">', $pegarelemento[$i]);
                if ($temp[0] != "" || $temp[0] != null) {
                    array_push($this->genero, $temp[0]);
                }
            }else{
            }
        }

        $pegarelemento= explode('<div class="row">
            Published',$datasite);
        if(isset($pegarelemento[1])){
            $this->anopublish = explode('</div>',$pegarelemento[1]);
        }else{
            $this->anopublish = ['',''];
        }

        //Trantando dados
        $this->Formatter();

        $goodreads = ["autor" => array("name"=>strval($this->autor[0]),"linkautor"=>$this->autorlink[0]),
                      "isbn" => array("isbn13"=>strval($this->isbn13[0]),"isbn10"=>$this->isbn10[0]),
                      "livro" => array("titulo"=>strval($this->titulo),
                                       "linguagem"=>$this->linguagem[0],
                                       "resumo"=>strval($this->resumo),
                                       "paginas"=>strval($this->paginas),
                                       "generos"=>$this->genero,
                                       "publicacao"=>$this->anopublish,
                                       "tipocapa"=>$this->tipocapa),
                      "link" => $this->link,
                      "image" => strval($this->imagem[0]),
                      "rating"=> array("score"=>floatval($this->rating[0]),
                      "reviews"=>intval($this->qyavaliantion[0]))];

        array_push($bodysearch,$goodreads);
        return $bodysearch;
    }
    private function GetbookPrices_($isbn10,$isbn13,$DESCONTO_){
         
        $SaraivaGetBook = new Crawling_Saraiva();
        $CulturaGetBook = new cultura();
        $AmazonGetBook = new amazon();
        //$AmericanasGetBook = new americanas();
        $ComixGetBook = new comix();

        if($isbn13 == "" || empty($isbn13)){
            $isbn13 = $isbn10;
        }
        
        if(!$DESCONTO_){
        $goodreads = ["amazon"=>$AmazonGetBook->Get_bookSimple($isbn10),
                      //"cultura"=>$CulturaGetBook->GetBook_Simple($isbn10),
                      "Saraiva"=>$SaraivaGetBook->Getbook_Simple($isbn13),
                      //"americanas"=>$AmericanasGetBook->GetBook_Simple($isbn13),
                      "comix"=>$ComixGetBook->GetBook_Simple($isbn13)
                    ];
        }else{
            $goodreads = ["amazon"=>$AmazonGetBook->Get_bookSimpleDesconto($isbn10),
                      "cultura"=>$CulturaGetBook->GetBook_Simple($isbn10),
                      "Saraiva"=>$SaraivaGetBook->Getbook_SimpleDesconto($isbn13),
                      "americanas"=>$AmericanasGetBook->GetBook_Simple2($isbn13),
                      "comix"=>$ComixGetBook->GetBook_SimpleDesconto($isbn13)
                    ];
        }

        return $goodreads;
    }
    private function Formatter(){

        //Tratando resumo
        $this->resumo =str_replace("\n", "",$this->resumo[0]);
        $this->resumo =str_replace("</p>", "",$this->resumo);
        $this->resumo = str_replace("\\n", "", $this->resumo);
        $this->resumo = str_replace("                ", "", $this->resumo);
        $this->resumo = strip_tags($this->resumo);

        //Tratando ISBN10
        $this->isbn10[0] =str_replace("\n", "",$this->isbn10[0]);
        $this->isbn10[0] =str_replace(" ", "",$this->isbn10[0]);
        $this->isbn10[0] =substr($this->isbn10[0],0,10);

        //Tratando Reviews
        $this->qyavaliantion[0]=str_replace(".", "",$this->qyavaliantion[0]);

        //Tratando tÃ­tulo
        $this->titulo =str_replace("\n", "",$this->titulo[0]);
        $this->titulo = trim($this->titulo);
        $this->titulo = strval($this->titulo);

         //Tratando tipo capa
         $this->tipocapa =str_replace('Hardcover', "Capa Dura",$this->tipocapa[0]);
         $this->tipocapa =str_replace('Paperback', "Brochadura",$this->tipocapa);

        //Tratando publicaÃ§Ã£o
        $this->anopublish =str_replace("\n", "",$this->anopublish[0]);
        $this->anopublish =str_replace("by", "por",$this->anopublish);
        $this->anopublish = strip_tags($this->anopublish);
        $this->anopublish= strval($this->anopublish);
        $this->anopublish = trim($this->anopublish);

        //Tratando link
        $this->link = "https://www.goodreads.com.br".$this->link[0];

        //Tratando linguagem
        $this->linguagem = str_replace("Portuguese","PortuguÃªs",$this->linguagem);

        //Tratando generos
        $genero1 = array();
        for($i=0;$i < count($this->genero);$i++) {
            $str_temp = str_replace(" ", "", $this->genero[$i]);
            $str_temp = str_replace("-", " ", $str_temp);
            $str_temp = ucwords($str_temp);
            $str_temp = $this->ConvertGeneros($str_temp);

            array_push($genero1,$str_temp);
        }
        $this->genero = $genero1;
    }
    private function FomatterLivroAutor(){

        //Tratamento de resumo
        $this->resumo =str_replace("\n", "",$this->resumo[0]);
        $this->resumo =substr($this->resumo,8,1000);

        //Tratamento de Nascimento
        $this->nascimento =str_replace("\n", "",$this->nascimento);
    }
    private function FomatterAboutAutor(){

        //Tratamento de Link
        $this->link = "https://www.goodreads.com".$this->link[0];
    }
    private function ConvertGeneros($stri){
        switch($stri){
            case('Horror'): return 'Terror';
                break;
            case('Fantasy'): return 'Fantasia';
                break;
            case('Fiction'): return 'FicÃ§Ã£o';
                break;
            case('Adventure'): return 'Aventura';
                break;
            case('Childrens'): return 'Infantil';
                break;
            case('Young Adult'): return 'Literatura Infantojuvenil';
                break;
            case('Classics'): return 'Literatura';
                break;
            case('Science Fiction Fantasy'): return 'Fantasia, Horror e FicÃ§Ã£o';
                break;
            case('Mystery'): return 'MistÃ©rio';
                break;
            case('Adult'): return 'Adulto';
                break;
            case('Thriller'): return 'Suspense';
                break;
            case('Literature'): return 'Literatura';
                break;
            case('Politics'): return 'PolÃ­tica';
                break;
            case('Science Fiction'): return 'FicÃ§Ã£o CientÃ­fica';
                break;
            case('Novels'): return 'Novela';
                break;
            case('Westerns'): return 'Faroeste';
                break;
            case('Poetry'): return 'Poesia';
                break;
            case('Adult Fiction'): return 'Ficção Adulto';
                break;
            case('Historical'): return 'História';
                break;
            case('Book Club'): return 'Mais Lidos';
                break;
            default: return $stri;
                break;
        }
    }
    private function ConvertMonths($stri){
        switch($stri){
            case('January'): return 'Janeiro';
                break;
            case('Feburary'): return 'Fevereiro';
                break;
            case('March'): return 'MarÃ§o';
                break;
            case('April'): return 'Abril';
                break;
            case('May'): return 'Maio';
                break;
            case('August'): return 'Agosto';
                break;
            case('June'): return 'Junho';
                break;
            case('July'): return 'Julio';
                break;
            case('September'): return 'Setembro';
                break;
            case('November'): return 'Novembro';
                break;
            case('Octuber'): return 'Outubro';
                break;
            case('December'): return 'Dezembro';
            break;
            default: return $stri;
                break;
        }
    }
}


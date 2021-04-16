<?php

namespace App\model\CrawlingModel;

class atualizador
{

    public function AtualizarSemDesconto($JSON){
        
        //Convertendo para Array
        $JSON = json_decode($JSON,true);

        //Atualizando Sessão Indice de Livros
        $JSON = $this->For_IndiceDelivros($JSON);

        //Atualizando Sessão England
        $JSON = $this->For_England($JSON);

        //Atualizando Sessão Brasil
        $JSON = $this->For_Brasil($JSON);

        //Atualizando Sessão Ghost
        $JSON = $this->For_ghost($JSON);

        //Atualizando Sessão IrelandX1
        $JSON = $this->For_IrelandX1($JSON);

        //Atualizando Sessão IrelandX2
        $JSON = $this->For_IrelandX2($JSON);

        //Atualizando Sessão IrelandX2
        $JSON = $this->For_IrelandX3($JSON);

        return $JSON;
    }

    private function GETLINKS($Array){

        echo "3t";
        
        $ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
        
        if(isset($Array['amazon'])){
        
            if($Array['amazon'][0]['price']['capadura'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capadura'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capadura']) ){
                $ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capadura'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon");
            }
            if($Array['amazon'][0]['price']['capacomum'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capacomum'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capacomum']) ){
                $ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capacomum'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon");
            }
        }
        
        if($Array['Saraiva']['preco'] < $ReturnNewArray['preco'] && $Array['Saraiva']['preco'] != "" && $Array['Saraiva']['preco'] != "0.00" && is_numeric($Array['Saraiva']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['Saraiva']['preco'],"link"=>$Array['Saraiva']['link'],"loja"=>"Saraiva");
        }
        if($Array['cultura']['preco'] < $ReturnNewArray['preco'] && $Array['cultura']['preco'] != "" && $Array['cultura']['preco'] != "0.00" && is_numeric($Array['cultura']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['cultura']['preco'],"link"=>$Array['cultura']['link'],"loja"=>"Cultura");
        }
        if($Array['americanas']['preco'] < $ReturnNewArray['preco'] && $Array['americanas']['preco'] != "" && $Array['americanas']['preco'] != "0.00" && is_numeric($Array['americanas']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['americanas']['preco'],"link"=>$Array['americanas']['link'],"loja"=>"Americanas");
        }
        if($Array['comix']['preco'] < $ReturnNewArray['preco'] && $Array['comix']['preco'] != "" && $Array['comix']['preco'] != "0.00" && is_numeric($Array['comix']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['comix']['preco'],"link"=>$Array['comix']['link'],"loja"=>"Comix");
        }
        
        return $ReturnNewArray;
    }
    private function GETLINKS_Amazon($Array){
        
        echo "2t";
        $ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
        
        if(isset($Array['amazon'])){
        
            if($Array['amazon'][0]['price']['capadura'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capadura'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capadura']) ){
                $ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capadura'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon");
            }
            if($Array['amazon'][0]['price']['capacomum'] <= $ReturnNewArray['preco'] && $Array['amazon'][0]['price']['capacomum'] != "0.00" && is_numeric($Array['amazon'][0]['price']['capacomum']) ){
                $ReturnNewArray = array("preco"=>$Array['amazon'][0]['price']['capacomum'],"link"=>$Array['amazon'][0]['link'],"loja"=>"Amazon");
            }
        }
        return $ReturnNewArray;
    }
    private function GETLINKS_Saraiva($Array){
        
        $ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
        
        
        if($Array['Saraiva']['preco'] < $ReturnNewArray['preco'] && $Array['Saraiva']['preco'] != "" && $Array['Saraiva']['preco'] != "0.00" && is_numeric($Array['Saraiva']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['Saraiva']['preco'],"link"=>$Array['Saraiva']['link'],"loja"=>"Saraiva");
        }
        
        return $ReturnNewArray;
    }
    private function GETLINKS_Cultura($Array){
        
        $ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
        
        if($Array['cultura']['preco'] < $ReturnNewArray['preco'] && $Array['cultura']['preco'] != "" && $Array['cultura']['preco'] != "0.00" && is_numeric($Array['cultura']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['cultura']['preco'],"link"=>$Array['cultura']['link'],"loja"=>"Cultura");
        }
        return $ReturnNewArray;
    }
    private function GETLINKS_Americanas($Array){
        
        $ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");
        
        if($Array['americanas']['preco'] < $ReturnNewArray['preco'] && $Array['americanas']['preco'] != "" && $Array['americanas']['preco'] != "0.00" && is_numeric($Array['americanas']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['americanas']['preco'],"link"=>$Array['americanas']['link'],"loja"=>"Americanas");
        }
        return $ReturnNewArray;
    }
    private function GETLINKS_Comix($Array){
        
        $ReturnNewArray = array("preco"=>"500000","link"=>"","loja"=>"Indice de Livros");

        if($Array['comix']['preco'] < $ReturnNewArray['preco'] && $Array['comix']['preco'] != "" && $Array['comix']['preco'] != "0.00" && is_numeric($Array['comix']['preco']) ){
            $ReturnNewArray = array("preco"=>$Array['comix']['preco'],"link"=>$Array['comix']['link'],"loja"=>"Comix");
        }
        
        return $ReturnNewArray;
    }

    //Funções Foreach
    private function For_IndiceDelivros($JSON_DECODE){

        echo "1";

        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1['home_book_indice'] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            if(isset($Array['amazon'][0]['price'])){
                $returnLowPrice =  $this->GETLINKS($PricesBooks);
            }
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
                $JSON1['home_book_indice'][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1['home_book_indice'][$count]["link"] = $returnLowPrice["link"];
                $JSON1['home_book_indice'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1['home_book_indice'][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1['home_book_indice'][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1['home_book_indice'][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

    private function For_England($JSON_DECODE){

        echo "2";
        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1['home_book_england'] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            
            $returnLowPrice =  $this->GETLINKS($PricesBooks);
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){
                $JSON1['home_book_england'][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1['home_book_england'][$count]["link"] = $returnLowPrice["link"];
                $JSON1['home_book_england'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1['home_book_england'][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1['home_book_england'][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1['home_book_england'][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

    private function For_Brasil($JSON_DECODE){
        
        echo "3";
        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1['home_book_brasil'] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            
            $returnLowPrice =  $this->GETLINKS($PricesBooks);
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){              
                $JSON1['home_book_brasil'][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1['home_book_brasil'][$count]["link"] = $returnLowPrice["link"];
                $JSON1['home_book_brasil'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1['home_book_brasil'][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1['home_book_brasil'][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1['home_book_brasil'][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

    private function For_ghost($JSON_DECODE){

        echo "4";
        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1['home_book_ghost'] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            
            $returnLowPrice =  $this->GETLINKS($PricesBooks);
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){              
                $JSON1['home_book_ghost'][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1['home_book_ghost'][$count]["link"] = $returnLowPrice["link"];
                $JSON1['home_book_ghost'][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1['home_book_ghost'][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1['home_book_ghost'][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1['home_book_ghost'][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

    private function For_IrelandX1($JSON_DECODE){
        echo "5";
        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1["home_book_ireland"]["xil1"] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            
            $returnLowPrice =  $this->GETLINKS_Saraiva($PricesBooks);
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){              
                $JSON1["home_book_ireland"]["xil1"][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1["home_book_ireland"]["xil1"][$count]["link"] = $returnLowPrice["link"];
                $JSON1["home_book_ireland"]["xil1"][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1["home_book_ireland"]["xil1"][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1["home_book_ireland"]["xil1"][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1["home_book_ireland"]["xil1"][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

    private function For_IrelandX2($JSON_DECODE){

        echo "6";
        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1["home_book_ireland"]["xil2"] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            
            $returnLowPrice =  $this->GETLINKS_Cultura($PricesBooks);
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){              
                $JSON1["home_book_ireland"]["xil2"][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1["home_book_ireland"]["xil2"][$count]["link"] = $returnLowPrice["link"];
                $JSON1["home_book_ireland"]["xil2"][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1["home_book_ireland"]["xil2"][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1["home_book_ireland"]["xil2"][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1["home_book_ireland"]["xil2"][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

    private function For_IrelandX3($JSON_DECODE){

        echo "7";
        $count = 0;
        $JSON1 = $JSON_DECODE;
        $API_GET = new getbookreferences();
        foreach($JSON1["home_book_ireland"]["xil3"] as $var){
            $returnLowPrice = null;
            
            if(!isset($var["isbn"])){
                $count++;
                continue;
            }
            
            //GET DATA
            $DATA = $API_GET->Getfullbook($var['isbn']);
            
            if(!isset($DATA)){
                $count++;
                continue;
            }
            
            $ISBN13 = $DATA[0]['isbn']['isbn13'];
            $ISBN10 = $DATA[0]['isbn']['isbn10'];

            $cont=1;
            do{
                $PricesBooks = $API_GET->GetbookPrices($ISBN10,$ISBN13);
                if($cont == 4){
                    break;
                }
                sleep(2);
            }while($PricesBooks["amazon"] == "");
            
            
            $returnLowPrice =  $this->GETLINKS_Amazon($PricesBooks);
            
            if(isset($returnLowPrice["preco"]) && $returnLowPrice["preco"] != "500000"){              
                $JSON1["home_book_ireland"]["xil3"][$count]["preco"] = $returnLowPrice["preco"];
                $JSON1["home_book_ireland"]["xil3"][$count]["link"] = $returnLowPrice["link"];
                $JSON1["home_book_ireland"]["xil3"][$count]["titulo"] = $DATA[0]['livro']['titulo'];
                $JSON1["home_book_ireland"]["xil3"][$count]["autor"] = $DATA[0]['autor']['name'];
                $JSON1["home_book_ireland"]["xil3"][$count]["loja"] = $returnLowPrice["loja"];
                $JSON1["home_book_ireland"]["xil3"][$count]["imagem"] =$DATA[0]['image'];
            }
            $count++;
            
        }
        return $JSON1;
    }

}
<?php

namespace App\model\CrawlingModel;

class pelandofertas
{
    private $titulo;
    private $preco;
    private $link;
    private $loja;
    private $imagem;

    public function BookOfertas ($page){
        return $this->BookOfertas_($page);
    }

    private function getOfertaSite($index){

        if(empty($index)){
            $index = 0;
        }

        $url = "https://www.pelando.com.br/grupo/entretenimento-e-lazer?page=".$index;

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
        return $datasite;
    }

    private function BookOfertas_($page){
        
        $datasite = $this->getOfertaSite($page);

        $bodyjson = array();

        for($i=1;$i<=20;$i++){

            $pegarelemento = explode('{"action":"goto_thread title","beacon":true}', $datasite);
            if(isset($pegarelemento[$i])){
                
                $booksQuantity_ = explode('</a></strong>', $pegarelemento[$i]);

                $booksQuantity_ = explode("R$", $booksQuantity_[0]);

                $booksQuantity_ = str_replace("Livro - ","",$booksQuantity_[0]);
                $booksQuantity_ = str_replace("Livro","",$booksQuantity_);
                $booksQuantity_ = str_replace("[Livro]","",$booksQuantity_);
                $booksQuantity_ = str_replace(">","",$booksQuantity_);
                $booksQuantity_ = substr($booksQuantity_,1,50);

                $booksQuantity_ = str_replace(" | ","",$booksQuantity_);
                $this->titulo = $booksQuantity_;
                $this->titulo = str_replace("\\n", "", $this->titulo);
                $this->titulo = str_replace(">\n ", "", $this->titulo);
                $this->titulo = str_replace("\n ", "", $this->titulo);
                $this->titulo = str_replace('"', "", $this->titulo);
                $this->titulo = str_replace("\n\t\t\t", "", $this->titulo);
                $this->titulo = substr($this->titulo,0,50);
            }else{
                $booksQuantity_ = null;
            }


            $pegarelemento = explode('<span class="overflow--fade">', $datasite);
            $pegarelemento = explode('</span></span><span class="thread-divider"></span><a', $pegarelemento[$i]);
            
            $booksQuantity_ = explode('fromW3-xl">', $pegarelemento[0]);  
            if(isset($pegarelemento[1])){              
                $booksQuantity_ = explode('</span>', $pegarelemento[0]);
                $booksQuantity_ = explode('R$', $booksQuantity_[0]);

                $this->preco = $booksQuantity_;

                if(isset($this->preco[1]) && is_numeric($this->preco[1])){
                    $this->preco = str_replace("\\n", "", $this->preco[1]);
                    $this->preco = str_replace(">\n ", "", $this->preco);
                    $this->preco = str_replace("\n ", "", $this->preco);
                    $this->preco = str_replace(" ", "", $this->preco);
                    $this->preco = str_replace(",", ".", $this->preco);
                    $this->preco = str_replace("\n\t\t\t", "", $this->preco);
                    $this->preco = number_format($this->preco,2,".",",");
                }else{
                    $this->preco = "OFF";
                }
            }else{
                $this->preco = "GRÁTIS";
            }

            $pegarelemento = explode('thread-image width--all-auto height--all-auto imgFrame-img cept-thread-img"', $datasite);
            if(isset($pegarelemento[$i])){
                $this->imagem = explode('.jpg"', $pegarelemento[$i]);
                $this->imagem = str_replace('src="', "", $this->imagem);
                $this->imagem = str_replace('\\n', "", $this->imagem);
                $this->imagem = str_replace('\n ', "", $this->imagem);
                $this->imagem = str_replace('>\n', "", $this->imagem);
                $this->imagem = str_replace("\n\t", "", $this->imagem);
                $this->imagem = $this->imagem[0].".jpg";
            }else{
                $image_ = [""];
            }

            $pegarelemento = explode('brandPrimary link">', $datasite);
            if(isset($pegarelemento[$i])){
                $this->loja = explode('</', $pegarelemento[$i]);
                $this->loja=$this->loja[0];
                $this->loja=substr($this->loja,0,12);
            }else{
                $loja = [""];
            }

            $DataArray = array("titulo"=>$this->titulo,
                               "imagem"=>$this->imagem,
                               "preco"=>$this->preco,
                               "loja"=>$this->loja,
                               "link"=>$this->linkbuild($this->titulo,$this->loja));

            if(!empty($this->titulo)){
                array_push($bodyjson,$DataArray);
            }
        }

        return $bodyjson;
    }

    private function linkbuild($titulo,$loja){

        $titulo = str_replace(" ","%20",$titulo);

        $link = array(  "Amazon"=>"https://www.amazon.com.br/s?k=",
                        "Americanas"=>"https://www.americanas.com.br/busca/",
                        "Saraiva"=>"https://busca.saraiva.com.br/busca?q=",
                        "Cultura"=>"https://www3.livrariacultura.com.br/livros/",
                        "Comix"=>"https://www.comix.com.br/catalogsearch/result/?q=",
                        "Submarino"=>"https://www.submarino.com.br/busca/",
                        "Shoptime"=>"https://www.shoptime.com.br/busca/",
                        "Magazine Lui"=>"https://www.magazineluiza.com.br/busca/",);

        $afiliado = array( "Amazon"=>"&i=stripbooks&language=pt_BR&__mk_pt_BR=ÅMÅŽÕÑ&linkCode=sl2&linkId=d8f4f476cfee830e3de704326ce8569a&tag=1236908-20&ref=as_li_ss_tl",
                        "Americanas"=>"?opn=AFLACOM&epar=b2wafiliados&franq=AFL-03-5925651",
                        "Saraiva"=>"",
                        "Cultura"=>"?utmi_cp=12571&utm_source=lomadee&utm_medium=afiliado&utm_content=34118170&utm_campaign=cpa&lmdsid=600436891646-7897-1605097360044",
                        "Comix"=>"?utm_source=lomadee&lmdsid=627336779155-10808-1609273962734",
                        "Submarino"=>"?opn=AFLNOVOSUB&epar=b2wafiliados&franq=AFL-03-5925651",
                        "Shoptime"=>"?opn=AFLSHOP&epar=b2wafiliados&franq=AFL-03-5925651",
                        "Magazine Lui"=>"");
        
        if(!isset($link[$loja])){
            return $link["Amazon"].str_replace(" ","%20",$titulo).$afiliado["Amazon"];
        }
            return $link[$loja].str_replace(" ","%20",$titulo).$afiliado[$loja];
    }
    
}
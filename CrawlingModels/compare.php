<?php

namespace App\model\CrawlingModel;

class compare
{

    private $saraiva_;
    private $amazon_;
    private $cultura_;

    function __construct(){
        $this->saraiva_ = new Crawling_Saraiva();
        $this->amazon_ = new amazon();
        $this->cultura_ = new cultura();
    }

    public function CompareBook($termData){
        return $this->CompareAllStore($termData,date("d/m/y h:m:s"));
    }

    private function CompareAllStore($termSearch,$dtrequest){

        $datacompare = array();

        //Construindo Array com dados de lojas
        //Pegando lista de produtos pesquisados na Amazon
        $amazoncompare = $this->amazon_->Search_term($termSearch);
        $saraivacompare = $this->saraiva_->Search_saraiva($termSearch);
        $culturacompare =$this->cultura_->Search_cultura($termSearch);


        //Contar resultado da pesquisa;
        $cont = 1;
        //Pegando dados de livro pesquisado da loja Amazon
        foreach ($amazoncompare  as $data => $key){
            if($key['price']['Capa Comum'] == "" || $key['price']['Capa Comum'] == null){
                $precotemp = $key['price']['Capa Dura'];
            }else{
                $precotemp =$key['price']['Capa Comum'];
            }

        $parse = ['titulo'=>$key['titulo'],
                 'preco'=>$precotemp,
                 'imagem'=>$key['imagem'],
                 'link'=>$key['link'],
                 'autor'=>$key['autor'],
                 'loja'=> 'Amazon',
                 'isbn'=> $key['isbn']];
            array_push($datacompare,$parse);
            $cont ++;
        }
        //Pegando dados de livro pesquisado da loja Saraiva
        foreach ($saraivacompare  as $data => $key){
            $parse = ['titulo'=>$key['titulo'],
                'preco'=>$key['preco'],
                'imagem'=>$key['imagem'],
                'link'=>$key['link'],
                'autor'=>$key['autor'],
                'loja'=>"Saraiva",
                'isbn'=>$key['isbn']];
            array_push($datacompare,$parse);
            $cont ++;
        }
        //Pegando dados de livro pesquisado da loja Cultura
        foreach ($culturacompare  as $data => $key){
            $parse = ['titulo'=>$key['titulo'],
                'preco'=>$key['preco'],
                'imagem'=>$key['imagem'],
                'link'=>$key['link'],
                'autor'=>$key['autor'],
                'loja'=>"Cultura",
                'isbn'=>$key['isbn']];
            array_push($datacompare,$parse);
            $cont ++;
        }

        //Criando roda pé
        $parse = [  "rpesquisa"=>$cont,
                    "timeone"=>strval($dtrequest),
                    "timewtwo"=>strval(date("d/m/y h:m:s"))];
        array_push($datacompare,$parse);
        //Return values
        return $datacompare;
    }
    public function ShowsBook($linkSearch,$page,$quantidade){
        return $this->_ShowsBooks($linkSearch,$page,$quantidade);
    }
    private function _ShowsBooks($term,$page,$quantidade){


        if($term == "Ofertas do Índice de Livros" || substr($term,0,18) == "Ofertas do Índice"){
            $pelando = new pelandofertas();
            return $pelando->BookOfertas($page);
        }

        $datacompare = array();
        //Construindo Array com dados de lojas
        //Pegando lista de produtos pesquisados na Amazon
        $amazoncompare = $this->amazon_->Amazon_BookSearch($term,$page,$quantidade);
        $saraivacompare = $this->saraiva_->Saraiva_BookSearch($term,$page,$quantidade);
        $culturacompare =$this->cultura_->Cultura_BookSearch($term,$page,$quantidade);


        //Contar resultado da pesquisa;
        $cont = 0;
        //Pegando dados de livro pesquisado da loja Amazon
        foreach ($amazoncompare  as $data => $key){
            if($key['price']['Capa Comum'] == "" || $key['price']['Capa Comum'] == null){
                $precotemp = $key['price']['Capa Dura'];
            }else{
                $precotemp =$key['price']['Capa Comum'];
            }

            $parse = ['titulo'=>$key['titulo'],
                 'preco'=>$precotemp,
                 'imagem'=>$key['imagem'],
                 'link'=>$key['link'],
                 'autor'=>$key['autor'],
                 'loja'=> 'Amazon',
                 'isbn'=> $key['isbn']];
            array_push($datacompare,$parse);
            $cont ++;
        }
        //Pegando dados de livro pesquisado da loja Saraiva
        foreach ($saraivacompare  as $data => $key){
            $parse = ['titulo'=>$key['titulo'],
                'preco'=>$key['preco'],
                'imagem'=>$key['imagem'],
                'link'=>$key['link'],
                'autor'=>$key['autor'],
                'loja'=>"Saraiva",
                'isbn'=>$key['isbn']];
            array_push($datacompare,$parse);
            $cont ++;
        }
        //Pegando dados de livro pesquisado da loja Cultura
        foreach ($culturacompare  as $data => $key){
            $parse = ['titulo'=>$key['titulo'],
                'preco'=>$key['preco'],
                'imagem'=>$key['imagem'],
                'link'=>$key['link'],
                'autor'=>$key['autor'],
                'loja'=>"Cultura",
                'isbn'=>$key['isbn']];
            array_push($datacompare,$parse);
            $cont ++;
        }

        //Criando roda pé
        $parse = [  "rpesquisa"=>$cont,
                    "timewtwo"=>strval(date("d/m/y h:m:s"))];
        array_push($datacompare,$parse);
        //Return values
        return $datacompare;
    }
}

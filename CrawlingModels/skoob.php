<?php

namespace App\model\CrawlingModel;

class skoob
{

    public function getbook($term,$page){
        return $this->getbook_($term,$page);
    }

    protected function getbook_($term_,$page_){

        $datasite = $this->CurlFunction_($term_,$page_);

        if($datasite == null || empty($datasite) || isset($datasite['code'])){
            return [""];
        }

        $Arraytemp = array();

        $pegarelemento = explode("<div class='contador'><b style='font-size:15px; color:#666666;'>", $datasite);
        if(isset($pegarelemento[1])){
            $variaveltemp = explode(' encontrados</b>', $pegarelemento[1]);
            $encontrado = utf8_encode($variaveltemp[0]);

            if(!is_numeric($encontrado)){
                return [""];
            }

            ($encontrado >30) ? $encontrado=30 : $encontrado = $encontrado;
        }else{
            return [""];
        }

        for($i=1;$i<=$encontrado;$i++){

            $HTML_ROOT = explode("<div class='detalhes' style='border:none;'>", $datasite);
            $HTML_ROOT = explode("</div></div></div></div><br clear='all' />", $HTML_ROOT[$i]);

            $pegarelemento = explode('style="font-size:15px;font-weight:bold;">', $HTML_ROOT[0]);
            if(isset($pegarelemento[1])){
                $variaveltemp = explode('</a><br />', $pegarelemento[1]);
                $titulo = utf8_encode($variaveltemp[0]);
            }else{
                $titulo = null;
            }

            $pegarelemento = explode('style="margin:5px 0 5px 0;">', $HTML_ROOT[0]);
            if(isset($pegarelemento[1])){
                $variaveltemp = explode('</a>', $pegarelemento[1]);
                $autor = utf8_encode($variaveltemp[0]);
            }else{
                $autor = null;
            }

            $pegarelemento = explode('<span>Ano:', $HTML_ROOT[0]);
            if(isset($pegarelemento[1])){
                $variaveltemp = explode('</span>', $pegarelemento[1]);
                $ano = utf8_encode($variaveltemp[0]);
            }else{
                $ano = null;
            }

            $pegarelemento = explode('<span style="color:#556677;font-size:10px;">', $HTML_ROOT[0]);
            if(isset($pegarelemento[1])){
                $variaveltemp = explode("</span><span class='bar'>", $pegarelemento[1]);
                $variaveltemp = strip_tags($variaveltemp[0]);
                $variaveltemp  =str_replace("\n", "",$variaveltemp);
                $variaveltemp  =str_replace(" ", "",$variaveltemp);
                if(is_numeric(substr($variaveltemp,0,4))){
                    $isbn = $variaveltemp;
                }else{
                    $isbn = "";
                }
            }else{
                $isbn = null;
            }

            $pegarelemento = explode("<span class='bar'> | </span><span>", $HTML_ROOT[0]);
            if(isset($pegarelemento[1])){
                $variaveltemp = explode("</span><span> | ", $pegarelemento[1]);
                $variaveltemp = strip_tags($variaveltemp[0]);
                $publisher =  utf8_encode($variaveltemp);
            }else{
                $publisher = null;
            }

            $HTML_ROOT = explode("<div class='box_lista_busca_vertical'>", $datasite);
            $HTML_ROOT = explode('<br class="clear">', $HTML_ROOT[$i]);

            $pegarelemento = explode('<star-rating rate="', $HTML_ROOT[0]);
            if(isset($pegarelemento[1])){
                $variaveltemp = explode('" type="', $pegarelemento[1]);
                $variaveltemp = strip_tags($variaveltemp[0]);
                $rank =  $variaveltemp;
            }else{
                $rank = null;
            }

            $pegarelemento = explode("<img src='https://cache.skoob.com.br/local/images/", $datasite);
            if(isset($pegarelemento[$i])){
                $variaveltemp = explode(".jpg", $pegarelemento[$i]);
                $variaveltemp = explode("/center/top/smart/filters:format(jpeg)/", $variaveltemp[0]);
                $imagem = $variaveltemp[1].".jpg";
            }else{
                $imagem = null;
            }


            $BodyToJSON =["id"=>$i,
                          "titulo"=>$titulo,
                          "publisher"=>$publisher,
                          "imagem"=>$imagem,
                          "autor"=>$autor,
                          "ano"=>$ano,
                          "rank"=>$rank,
                          "isbn"=>$isbn];

            array_push($Arraytemp,$BodyToJSON);
        }

        return $Arraytemp;

    }
    protected function CurlFunction_($termToConsulting_,$page){

        $ch = curl_init();
        $url = "https://www.skoob.com.br/livro/lista/busca:".$this->SlugTermToConsulting_($termToConsulting_)."/tipo:geral/mpage:".$page;

        try{
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
        
            $datasite = curl_exec($ch);
            curl_close($ch);
        }catch (Exception $e){
            return ['code'=>"404",
                    "mensage"=>"Error Curl"];
        }
        return $datasite;
    }
    protected function SlugTermToConsulting_($term_){

        $term_ =  preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/",
                                "/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/",
                                "/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/",
                                "/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/",
                                "/(Ñ)/"),explode(" ",
                                "a A e E i I o O u U n N"),$term_);

        return str_replace(" ","+",$term_);
    }
}
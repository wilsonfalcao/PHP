<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $titulo = null;
        $autor = null;
        $publisher = null;
        $imagem = null;
        $isbn = null;
        $rank = null;
        $ano = null;

        $url = "https://www.skoob.com.br/livro/lista/busca:O+medico+e+o+monstro/tipo:geral/mpage:2";

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
            echo $datasite;
            exit;
        }

        $Arraytemp = array();


        for($i=1;$i<=30;$i++){

            $HTML_ROOT = explode("<div class='detalhes' style='border:none;'>", $datasite);
            $HTML_ROOT = explode('<i class="icon-cart" style="font-size: 14px;', $HTML_ROOT[$i]);

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
                if(is_numeric($variaveltemp)){
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
                $imagem = "https://cache.skoob.com.br/local/images/".$variaveltemp[0];
            }else{
                $imagem = null;
            }


            $BodyToJSON =["titulo"=>$titulo,
                          "publisher"=>$publisher,
                          "imagem"=>$imagem,
                          "autor"=>$autor,
                          "ano"=>$ano,
                          "rank"=>$rank,
                          "isbn"=>$isbn];

            array_push($Arraytemp,$BodyToJSON);
        }
        print_r($Arraytemp);
        print_r(json_encode($Arraytemp));
?>

        
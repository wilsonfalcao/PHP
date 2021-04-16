<?php

		$titulo = "null";
        $price = "null";
        $link = "null";

		
		$term_ = "9788574526003";
		$page = 1;
		$term_ = str_replace(" ","%20",$term_);
        $url = "https://www.americanas.com.br/busca/".$term_."?sortBy=lowerPrice";

        $datasite = file_get_contents($url);

        $bodysearch = array();

        for ($i = 1; $i <= 1; $i++) {
           
            $pegarelemento = explode('<span class="src__Text-sc-154pg0p-0 src__Name-sc-1k0ejj6-3 dSRUrl">', $datasite);
            if(isset($pegarelemento[$i])) {
                $titulo = explode('</span><div size="13"', $pegarelemento[$i]);
                $titulo = $titulo[0];
            }else{
                $titulo = "";
            }
			$pegarelemento = explode('src__PromotionalPrice-sc-1k0ejj6-7 iIPzUu">R$ <!-- -->', $datasite);
            if(isset($pegarelemento[$i])) {
                $price = explode('</span><span class="src_', $pegarelemento[$i]);
                $price = $price[0];
            }else{
                $price = "";
            }
			$pegarelemento = explode('<div class="src__Wrapper-sc-1k0ejj6-2 dGIFSc"><a to="', $datasite);
            if(isset($pegarelemento[$i])) {
                $link= explode('" config="[object Object]"', $pegarelemento[$i]);
                $link= "https://www.americanas.com.br".$link[0];
            }else{
                $link = "";
            }
            $saraivabook = ['titulo'=> $titulo,
                            'preco' => $price,
                            'link'=>strval($link)];
           array_push($bodysearch, $saraivabook);
        }
        echo json_encode($bodysearch);
?>
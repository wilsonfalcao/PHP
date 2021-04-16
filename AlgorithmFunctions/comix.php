<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
		$titulo = "null";
        $price = "0,00";
        $link = "null";
        $autor = "null";
        $imagem = "null";
        $isbn= "null";
        $descricao = null;
        $publisher = null;
        $categoria = null;
        $text= "Box Trilogia O Senhor dos Anéis";
        $text = str_replace(" ","%20",$text);
        $url = "https://www.googleapis.com/books/v1/volumes?q=".$text."&langRestrict=pt&printType=BOOKS&startIndex=0&maxResults=20";

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

        $dataArray = json_decode($datasite,true);
        echo "<br><br><br><br><br>";
        echo "<h1>teste</h1>";
        foreach($dataArray["items"]  as $value){
            echo $value["volumeInfo"]["title"]."<br>";
            echo $value["volumeInfo"]["industryIdentifiers"][0]["identifier"]."<br>";
            echo '<img src='.$value["volumeInfo"]["imageLinks"]["thumbnail"].'>';
            echo $value["volumeInfo"]["authors"][0]."<br>";
            echo $value["volumeInfo"]["publisher"]."<br>";
            echo $value["volumeInfo"]["description"]."<br><br><br>";
            echo $value["volumeInfo"]["previewLink"]."<br><br><br>";

        }

        echo $datasite;
?>

        
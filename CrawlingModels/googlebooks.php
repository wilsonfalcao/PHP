<?php

namespace App\model\CrawlingModel;

class googlebooks
{

    private $PUBLISH_;
    private $ISB13_;
    private $AUTOR_;
    private $IMG_;
    private $DESCR_;
    private $ISBN_;


    //Encapsulamento de Métodos
    public function SearchBooksByGoogle($term){
        return $this->SearchBooksByGoogle_($term);
    }

    public function SearchBooksByGooglefull($term,$page,$restriction){
        return $this->SearchBooksByGooglefull_($term,$page,$restriction);
    }


    //Métodos da classe GoogleBooks
    protected function SearchBooksByGoogle_($term_){

        if(!$datasite = $this->CurlFunction_($term_)){
            return [""];
        }

        $dataArray = json_decode($datasite,true);

        $ArrayToJSON = [""];

        foreach($dataArray["items"]  as $value){

            $this->AnalyseArray($value);
            
            $ArrayTemp = array(
                "titulo"=>$value["volumeInfo"]["title"],
                "isbn"=>$value["volumeInfo"]["industryIdentifiers"][0]["identifier"],
                "isbn13"=>$this->ISB13_,
                "autor"=>$this->AUTOR_,
                "linkpreview"=>$value["volumeInfo"]["previewLink"],
                "imagem"=>$this->IMG_,
                "publisher"=>$this->PUBLISH_,
                "description"=>$this->DESCR_,
            );
            
            if(!empty($value["volumeInfo"]["title"])){
                array_push($ArrayToJSON,$ArrayTemp);
            }

            $ArrayTemp = null;
        }

        return $ArrayToJSON;

    }

    protected function SearchBooksByGooglefull_($term_,$page_,$restriction_){

        if(!$datasite = $this->CurlFunction2_(
            array(
                   "term"=>$term_,
                    "page"=>$page_,
                    "restriction"=>$restriction_
                    ))){
            return [""];
        }

        $dataArray = json_decode($datasite,true);

        $ArrayToJSON = [""];

        foreach($dataArray["items"]  as $value){

            $this->AnalyseArray($value);
            
            $ArrayTemp = array(
                "titulo"=>$value["volumeInfo"]["title"],
                "isbn"=>$this->ISBN_,
                "isbn13"=>$this->ISB13_,
                "autor"=>$this->AUTOR_,
                "linkpreview"=>$value["volumeInfo"]["previewLink"],
                "imagem"=>$this->IMG_,
                "publisher"=>$this->PUBLISH_,
                "description"=>$this->DESCR_,
            );
            
            if(!empty($value["volumeInfo"]["title"])){
                array_push($ArrayToJSON,$ArrayTemp);
            }

            $ArrayTemp = null;
        }

        return $ArrayToJSON;

    }

    protected function CurlFunction_($termToConsulting_){

        $ch = curl_init();
        $url = "https://www.googleapis.com/books/v1/volumes?q=".$this->SlugTermToConsulting_($termToConsulting_)."&langRestrict=pt&printType=BOOKS&startIndex=0&maxResults=20";

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

    protected function CurlFunction2_($termToConsulting_){

        $term = $termToConsulting_["term"];
        $page = $termToConsulting_["page"];
        $restriction = $termToConsulting_["restriction"];
        $page = intval($page)*10;

        $ch = curl_init();

        if($restriction == "true"){
            $url = "https://www.googleapis.com/books/v1/volumes?q=".$this->SlugTermToConsulting_($term)."&langRestrict=pt&printType=BOOKS&startIndex=".$page."&maxResults=20";
        }else{
            $url = "https://www.googleapis.com/books/v1/volumes?q=".$this->SlugTermToConsulting_($term)."&printType=BOOKS&startIndex=".$page."&maxResults=20";
        }

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
        return str_replace(" ","%20",$term_);
    }

    protected function AnalyseArray($value){

        if(!empty($value["volumeInfo"]["industryIdentifiers"][1]["identifier"])){
            $this->ISB13_ = $value["volumeInfo"]["industryIdentifiers"][1]["identifier"];
        }else{
            $this->ISB13_ = null;
        }

        if(!empty($value["volumeInfo"]["authors"][0])){
            $this->AUTOR_ = $value["volumeInfo"]["authors"][0];
        }else{
            $this->AUTOR_ = null;
        }

        if(!empty($value["volumeInfo"]["description"])){
            $this->DESCR_ = $value["volumeInfo"]["description"];
        }else{
            $this->DESCR_ = null;
        }

        if(!empty($value["volumeInfo"]["imageLinks"]["thumbnail"])){
            $this->IMG_ = $value["volumeInfo"]["imageLinks"]["thumbnail"];
        }else{
            $this->IMG_  = null;
        }

        if(!empty($value["volumeInfo"]["publisher"])){
            $this->PUBLISH_ = $value["volumeInfo"]["publisher"];
        }else{
            $this->PUBLISH_  = null;
        }

        if(!empty($value["volumeInfo"]["industryIdentifiers"][0]["identifier"])){
            $this->ISBN_ = $value["volumeInfo"]["industryIdentifiers"][0]["identifier"];
        }else{
            $this->ISBN_  = null;
        }

    }
}
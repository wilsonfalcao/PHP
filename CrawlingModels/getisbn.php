<?php

namespace App\model\CrawlingModel;

class getisbn
{
    private $ISBN10;
    private $ISBN13;

    private function getDetail(){
        return false;
    }

    public function getDetail_($term){

        $url = "https://isbndb.com/search/books/".$term;

        $datasite = file_get_contents($url);

        $bodysearch = array();

        $pegarelemento= explode('<dt> <strong>ISBN:</strong> ',$datasite);
        $this->ISBN10 = explode('</dt>',$pegarelemento[1]);

        $isbnsearch = ["isbn10" => $this->ISBN10[0]];

        if(!$this->ISBN10 == null || !$this->ISBN10  == "" || !isset($this->ISBN10 )){
            array_push($bodysearch,$isbnsearch);
        }
        $isbnsearch= null;
        return $bodysearch;
    }
}

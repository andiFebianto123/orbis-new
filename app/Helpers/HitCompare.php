<?php

namespace App\Helpers;

use App\Helpers\HitApi;

class HitCompare {
    private $fields;
    private $request;
    private $module;

    function addFieldCompare(
        Array $fields, 
        Array $req
    ){
        $this->fields = $fields;
        $this->request = $req;
    }
    public function compareData(Array $data){
        // $data itu adalah array dari entry database
        $trigger = 0;
        if(count($this->fields) > 0){
            foreach($this->fields as $key => $value){
                $data_table = trim($data[$key]);
                $data_request = trim($this->request[$value]);
                if($data_table != $data_request){
                    $trigger = 1;
                    break;
                }
            }
        }

        if($trigger){
            // brati ada perubahan
            return $data['id'];
        }
        return false;
    }
}
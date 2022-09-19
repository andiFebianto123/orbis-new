<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class HitApi {
    private $api = "https://api.testing.ifgf-global.eigen.co.id/api/v1/synchron/pastoral";
    // private $api = "https://jsonplaceholder.typicode.com/posts";

    function action(Array $paramsId, String $action, String $module){
        $d = [
            'ids' => $paramsId,
            'action' => $action,
            'module' => $module
        ];
        $response = Http::post($this->api, $d); 
        return $response;
    }
}
?>
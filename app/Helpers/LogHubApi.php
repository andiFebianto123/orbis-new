<?php
namespace App\Helpers;

use App\Models\LogHub;
use App\Models\Personel;

class LogHubApi
{
    public function save($personel_id, $action, $category)
    {
        if(Personel::where('id', $personel_id)->exists()){
            $personel = Personel::where('id', $personel_id)->first();
            $log_hub = new LogHub();
            $log_hub->personel_id = $personel->id;
            $log_hub->name = $personel->first_name. " ".$personel->last_name;
            $log_hub->email = $personel->email;
            $log_hub->category = $category;
            $log_hub->user_agent =  isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"unkown";
            $log_hub->ip = $this->getClientIp();
            $log_hub->action =  $action;
            $log_hub->save();
        }
    }

    private function getClientIp() {
        $external_content = file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $external_content, $m);
        $external_ip = $m[1];

        return $external_ip;
    }
}
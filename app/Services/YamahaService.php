<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class YamahaService
{

    private function getBaseUrl(){
        return "http://".getenv('YAMAHA_DEVICE_IP').'/YamahaExtendedControl/v1/netusb/';
    }

    private function makeRequest($function,$params){
        if(!isset($params['input'])) {
            $params['input'] = 'usb';
        }
        $url = $this->getBaseUrl().$function.'?'.http_build_query($params);
        echo $url;
        $result = Http::get($url);
        //print_r($result->body());
        //print_R("<hr>");
        return $result;
    }

    public function makeReturnRequest(){
        return $this->makeRequest('setListControl',['type'=>'return']);
    }

    public function makeSelectRequest($index){
        return $this->makeRequest('setListControl',['type'=>'select', 'index'=>$index]);
    }

    private function makePlayRequest($index){
        return $this->makeRequest('setListControl',['type'=>'play', 'index'=>$index]);
    }

    public function makeListRequest($index){
        return $this->makeRequest('getListInfo',['type'=>'select', 'index'=>$index, 'size'=>8]);
    }

    public function goToRootDirectory(){
        for($i=0;$i<50;$i++){
            $result = $this->makeReturnRequest();
            $body= json_decode($result->body());
            if($body->response_code==4){break;}
        }
        $this->makeListRequest(0);

    }

    public function play($indexPath, $fileIndex){

        $this->goToRootDirectory();
        $pathParts = explode(' ',$indexPath);
        foreach($pathParts as $part) {
            $this->makeSelectRequest($part);
            $this->makeListRequest(0);
        }
        $this->makePlayRequest($fileIndex);
    }
}

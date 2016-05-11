<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SearchController extends Controller
{
    
    public function getList($param){
        $arr_result = array();
        //$param = "277";
        $api_key = "5eede808-9821-4fce-beb6-cd5cb5a91e11";
        $stop_Service_url = 'http://api.at.govt.nz/v1/gtfs/stops/search/'.$param.'?api_key='.$api_key;
        $bus_Service_url = 'http://api.at.govt.nz/v1/gtfs/routes/routeShortName/'.$param.'?api_key='.$api_key;

        $stop_Results = json_decode(file_get_contents($stop_Service_url), true);
        $stop_response = $stop_Results['response'];

        foreach ($stop_response as $item) {
            $arr = array("id"=>$item['stop_id'], "num"=>$item['stop_code'], "name"=>$item['stop_name'], "type"=>"S");
            array_push($arr_result, $arr);
        }

        $bus_Results = json_decode(file_get_contents($bus_Service_url), true);
        $bus_response = $bus_Results['response'];

        foreach ($bus_response as $item) {

            $arr = array("id"=>$item['route_id'], "num"=>$item['route_short_name'], "name"=>$item['route_long_name'], "type"=>"B");
            array_push($arr_result, $arr);
        }

        echo count($arr_result);
        var_dump($arr_result);
    }
    
}

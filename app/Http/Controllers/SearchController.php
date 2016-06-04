<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

class SearchController extends Controller
{
    
    public function getList($param){
        $searchListResult  = array();
        //$param = "277";
        $api_key = "5eede808-9821-4fce-beb6-cd5cb5a91e11";
        $stop_Service_url = 'http://api.at.govt.nz/v1/gtfs/stops/search/'.$param.'?api_key='.$api_key;
        $bus_Service_url = 'http://api.at.govt.nz/v1/gtfs/routes/routeShortName/'.$param.'?api_key='.$api_key;

        $stop_Results = json_decode(file_get_contents($stop_Service_url), true);
        $stop_response = $stop_Results['response'];

        foreach ($stop_response as $item) {
            
            if(strpos($item['stop_id'], $param) > 0){
                $arr = array("id"=>$item['stop_id'], "num"=>$item['stop_code'], "name"=>$item['stop_name'], "type"=>"S");
                array_push($searchListResult , $arr);   
            }
        }

        $bus_Results = json_decode(file_get_contents($bus_Service_url), true);
        $bus_response = $bus_Results['response'];

        foreach ($bus_response as $item) {

            $arr = array("id"=>$item['route_id'], "num"=>$item['route_short_name'], "name"=>$item['route_long_name'], "type"=>"B");
            array_push($searchListResult , $arr);
        }

        return $searchListResult;

    }

    public function getTimeTable($param){
        $searchResult  = array();
        $searchListResult  = array();
        //jimin : a67a9662-fa06-464a-a8ab-ba1598dd4523
        //mine : 5eede808-9821-4fce-beb6-cd5cb5a91e11
        //ken : 03e4fe7b-8f40-4183-aded-d1e8a7b95b9e
        //EJ : f9780d34-cb88-4c39-91fd-f604316b591e  
        $api_key = "a67a9662-fa06-464a-a8ab-ba1598dd4523";
        $stop_Service_url = 'http://api.at.govt.nz/v1/gtfs/stops/stopId/'.$param.'?api_key='.$api_key;
        $timeTable_Service_url = 'http://api.at.govt.nz/v1/gtfs/stopTimes/stopId/'.$param.'?api_key='.$api_key;
        $routes_Service_url = 'http://api.at.govt.nz/v1/gtfs/routes/stopid/'.$param.'?api_key='.$api_key;


        $opts = array(
          'http'=>array(
            'timeout'=>10,
          )
        );

        $context = stream_context_create($opts);
        $stop_Results = json_decode(file_get_contents($stop_Service_url, false, $context), true);
        $stop_response = $stop_Results['response'];
        
        foreach($stop_response as $item){
            $arr = array('id' => $item['stop_id'], 'num' => $item['stop_code'], 'name' => $item['stop_name'], 'type' => 'S');
            array_push($searchResult, $arr);
        }

        $timeTable_Results = json_decode(file_get_contents($timeTable_Service_url, false, $context), true);
        $timeTable_response = $timeTable_Results['response'];


        $routes_Results = json_decode(file_get_contents($routes_Service_url, false, $context), true);
        $routes_response = $routes_Results['response'];

        

        date_default_timezone_set('NZ');
        $currentTime =  date("H:i:s", time());
        $afterHourTime = date("H:i:s",strtotime(" + 1 hour ")) ;
        
        foreach ($timeTable_response as $timeItem) {

            $routeId = "";
            $busNum = "";
            $busDesc = "";
            if($currentTime < $timeItem['arrival_time'] &&  $afterHourTime > $timeItem['arrival_time']){    

            
                $strTripId = $timeItem['trip_id'];
                $trips_Service_url = 'http://api.at.govt.nz/v1/gtfs/trips/tripid/'.$strTripId.'?api_key='.$api_key;
                $trips_Service_url;
                $trips_Results = json_decode(file_get_contents($trips_Service_url, false, $context), true);
                $trips_response = $trips_Results['response'];
                if($trips_response){
                    
                    foreach($trips_response as $tripItem){
                        if($timeItem['trip_id'] == $tripItem['trip_id']){

                            foreach($routes_response as $routeItem){
                                if($routeItem['route_id'] == $tripItem['route_id']){
                                    $routeId = $routeItem['route_id'];
                                    $busNum = $routeItem['route_short_name'];
                                    $busDesc = $routeItem['route_long_name'];
                                }
                            }
                        }

                    }
                        
                }

                $arr = array("trip_id"=>$timeItem['trip_id'], 
                            "route_id"=>$routeId,
                            "num"=>$busNum, 
                            "name"=>$busDesc, 
                            "arr_time"=>$timeItem['arrival_time'] );
                array_push($searchListResult , $arr);
            }
        }

        return $searchListResult;
        
    }
    
}

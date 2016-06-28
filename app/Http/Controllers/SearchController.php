<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Stop;

class SearchController extends Controller
{
    //Get random API Key
    public function getRandomApi(){
        $my_array = array('5eede808-9821-4fce-beb6-cd5cb5a91e11', 
                            'a67a9662-fa06-464a-a8ab-ba1598dd4523', 
                            '03e4fe7b-8f40-4183-aded-d1e8a7b95b9e', 
                            'f9780d34-cb88-4c39-91fd-f604316b591e',
                            'bccdd7dd-30f9-4038-b45b-6b1ac78a3fea',
                            '0a1a548a-de8c-43ab-96c2-c56ffd61d8b3',
                            'b16546f0-385b-4fc8-a9ce-953996573872');
        return $my_array[array_rand($my_array, 1)];
    }

    //Search Bus & Stop List from main
    public function getList($param){
        $searchListResult  = array();

        $api_key =  $this->getRandomApi();
     
        //Search Stop List
        $stop_Service_url = 'http://api.at.govt.nz/v1/gtfs/stops/search/'.$param.'?api_key='.$api_key;
        //Search Bus List 
        $bus_Service_url = 'http://api.at.govt.nz/v1/gtfs/routes/routeShortName/'.$param.'?api_key='.$api_key;   
        try{
            
            $stop_Results = json_decode(file_get_contents($stop_Service_url), true);
            $stop_response = $stop_Results['response'];

            //make stop data structure
            foreach ($stop_response as $item) {
                
                if(strpos($item['stop_id'], $param) > 0){
                    $arr = array("id"=>$item['stop_id'], "num"=>$item['stop_code'], "name"=>$item['stop_name'], "type"=>"S");
                    array_push($searchListResult , $arr);   
                }
            }

            //make bus data structure
            $bus_Results = json_decode(file_get_contents($bus_Service_url), true);
            $bus_response = $bus_Results['response'];

            foreach ($bus_response as $item) {

                $arr = array("id"=>$item['route_id'], "num"=>$item['route_short_name'], "name"=>$item['route_long_name'], "type"=>"B");
                array_push($searchListResult , $arr);
            }

            return $searchListResult;

        }catch(\Exception $e){
            
            return $e->getMessage();

        }

    }

    //Get Stop List from AT then insert to Database
    public function getStopList(){

        //Search Stop List From AT to save data in Database
        $searchStopList = array();

        $api_key = $this->getRandomApi();
        //$stop_service_url = 'http://api.at.govt.nz/v1/gtfs/stops/stopId/3382?api_key='.$api_key;
        $stop_service_url = 'http://api.at.govt.nz/v1/gtfs/stops?api_key='.$api_key;

        try{

            //Get Stop List
            $results = json_decode(file_get_contents($stop_service_url), true);
            $response = $results['response'];

            if(count($response) > 0){
                //Insert Stop list to Database
                $stop = Stop::truncate();
                foreach($response as $item){

                    //
                    $stop = new Stop();
                    $stop -> stop_id = $item['stop_id'];
                    $stop -> stop_name = $item['stop_name'];
                    $stop -> stop_desc = $item['stop_desc'];
                    $stop -> stop_lat = $item['stop_lat'];
                    $stop -> stop_lon = $item['stop_lon'];
                    $stop -> zone_id = $item['zone_id'];
                    $stop -> stop_url = $item['stop_url'];
                    $stop -> stop_code = $item['stop_code'];
                    $stop -> stop_street = $item['stop_street'];
                    $stop -> stop_city = $item['stop_city'];
                    $stop -> stop_region = $item['stop_region'];
                    $stop -> stop_postcode = $item['stop_postcode'];
                    $stop -> stop_country = $item['stop_country'];
                    $stop -> location_type = $item['location_type'];
                    $stop -> parent_station = $item['parent_station'];
                    $stop -> stop_timezone = $item['stop_timezone'];
                    $stop -> wheelchair_boarding = $item['wheelchair_boarding'];
                    $stop -> direction = $item['direction'];
                    $stop -> position = $item['position'];
                    $stop -> the_geom = $item['the_geom'];

                    $stop -> save();

                }
            }
            
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
    
    //Search Time Table with stop Id
    public function getTimeTable($param){
        $searchResult  = array();
        $searchListResult  = array();

        $api_key =  $this->getRandomApi();
        $stop_Service_url = 'http://api.at.govt.nz/v1/gtfs/stops/stopId/'.$param.'?api_key='.$api_key;
        $timeTable_Service_url = 'http://api.at.govt.nz/v1/gtfs/stopTimes/stopId/'.$param.'?api_key='.$api_key;
        $routes_Service_url = 'http://api.at.govt.nz/v1/gtfs/routes/stopid/'.$param.'?api_key='.$api_key;


        $opts = array(
          'http'=>array(
            'timeout'=>10,
          )
        );

        try{
            //set context with timeout
            $context = stream_context_create($opts);

            //Search stop list
            $stop_Results = json_decode(file_get_contents($stop_Service_url, false, $context), true);
            $stop_response = $stop_Results['response'];
            //make stop data structure
            foreach($stop_response as $item){
                $arr = array('id' => $item['stop_id'], 'num' => $item['stop_code'], 'name' => $item['stop_name'], 'type' => 'S');
                array_push($searchResult, $arr);
            }


            //Search time table list with stop Id
            $timeTable_Results = json_decode(file_get_contents($timeTable_Service_url, false, $context), true);
            $timeTable_response = $timeTable_Results['response'];

            //Search route for get bus information
            $routes_Results = json_decode(file_get_contents($routes_Service_url, false, $context), true);
            $routes_response = $routes_Results['response'];

            
            //set time zone 
            date_default_timezone_set('NZ');
            $currentTime =  date("H:i:s", time());
            $afterHourTime = date("H:i:s",strtotime(" + 1 hour ")) ;
            
            //make data structure
            foreach ($timeTable_response as $timeItem) {

                $routeId = "";
                $busNum = "";
                $busDesc = "";

                //get time table list in current time
                if($currentTime < $timeItem['arrival_time'] &&  $afterHourTime > $timeItem['arrival_time']){    

                    //get trip information for getting route id
                    $strTripId = $timeItem['trip_id'];
                    $trips_Service_url = 'http://api.at.govt.nz/v1/gtfs/trips/tripid/'.$strTripId.'?api_key='.$api_key;
                    $trips_Service_url;
                    $trips_Results = json_decode(file_get_contents($trips_Service_url, false, $context), true);
                    $trips_response = $trips_Results['response'];
                    if($trips_response){
                        
                        foreach($trips_response as $tripItem){
                            if($timeItem['trip_id'] == $tripItem['trip_id']){
                                //get bus information from route
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

        }catch(\Exception $e){

            return $e->getMessage();
        }
        
    }

    //Search Stop list about bus
    public function getStopListByRouteId($routeId){
        
        $searchListResult  = array();

        $api_key = $this->getRandomApi();
        //27701-20160622152022_v42.15
        $trip_service_url = 'http://api.at.govt.nz/v1/gtfs/trips/routeid/'.$routeId.'?api_key='.$api_key;

        try{

            //Search stop list
            $trip_Results = json_decode(file_get_contents($trip_service_url), true);
            $trip_response = $trip_Results['response'][0];
            $trip_id = $trip_response['trip_id'];
            
            $stopList_service_url = 'http://api.at.govt.nz/v1/gtfs/stops/tripId/'.$trip_id.'?api_key='.$api_key;
            $stopList_Results = json_decode(file_get_contents($stopList_service_url), true);
            $stopList_response = $stopList_Results['response'];
            
            return $stopList_response;


        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}

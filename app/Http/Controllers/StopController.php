<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Controller\SearchController;

use App\Models\Stop;

use Input;
use Resirect;

class StopController extends Controller
{


    /**
     * Search Stop List By Id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStopListById($id){
        try{

            $stop = Stop::where('stop_id','LIKE', $id.'%')->get();
            return $stop;

        }catch(\Exception $e){
            return $e->getMessage();
        }
    }



    /**
     * Search Stop list near me by stop id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStopListNearMeById($id){
        try{
            
            //when user search stop list with stop id
            $stop = Stop::where('stop_id', $id)->get();
            $orig_lat = $stop[0]['stop_lat'];
            $orig_lon = $stop[0]['stop_lon'];
            $bounding_distance = 0.4;

            //near stop list searched with lat & lon and get distance then get list which distance is less than 0.4
            $stopsNearMe = Stop::select(    
                                \DB::raw('*, ((ACOS(SIN('.$orig_lat.'  * PI() / 180) * SIN(stop_lat * PI() / 180) 
                                             + COS('.$orig_lat.'  * PI() / 180) 
                                             * COS(stop_lat * PI() / 180) 
                                             * COS(
                                                ('.$orig_lon.' - stop_lon) * PI() / 180)) 
                                             * 180 / PI()) 
                                            * 60 * 1.1515
                                        ) AS distance'))
                                ->having("distance", "<", $bounding_distance)
                                ->orderBy("distance")
                                ->get();
            
            return $stopsNearMe;

        }catch(\Exception $e){

        }
    }

    /**
     * Search Stop list near me by stop id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStopListNearMeBylatlon($location){
        try{

            //get location like '-36.91773,174.74901'
            //split location with ','
            //first is lat, second is lon
            $orig_lat = explode(",",$location)[0];
            $orig_lon = explode(",",$location)[1];
            $bounding_distance = 0.4;

            //near stop list searched with lat & lon and get distance then get list which distance is less than 0.4
            $stopsNearMe = Stop::select(    
                                \DB::raw('*, ((ACOS(SIN('.$orig_lat.'  * PI() / 180) * SIN(stop_lat * PI() / 180) 
                                             + COS('.$orig_lat.'  * PI() / 180) 
                                             * COS(stop_lat * PI() / 180) 
                                             * COS(
                                                ('.$orig_lon.' - stop_lon) * PI() / 180)) 
                                             * 180 / PI()) 
                                            * 60 * 1.1515
                                        ) AS distance'))
                                ->having("distance", "<", $bounding_distance)
                                ->orderBy("distance")
                                ->get();
            
            return $stopsNearMe;



        }catch(\Exception $e){

        }
    }
}

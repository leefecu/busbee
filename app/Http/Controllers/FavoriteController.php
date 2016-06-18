<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Favorite;

use Input;
use Redirect;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo "Favorite Create Page";
        return view('favorite.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save favorite list
        $user_id = Input::get('user_id');
        $description = Input::get('description');
        $type = Input::get('type');
        $target_id = Input::get('target_id');
        $target_name = Input::get('target_name');

        try{

            $favorite = new Favorite();
            $favorite -> user_id = $user_id;
            //Stop or Bus 's direction
            $favorite -> description = $description;
            //S(stop) or B(bus)
            $favorite -> type = $type;
            //Stop code or Route id
            $favorite -> target_id = $target_id;
            //Stop name or Bus Number
            $favorite -> target_name = $target_name;
            $favorite -> save();

            return 'Success';

        }catch(\Exception $e){
            return $e->getMessage();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{

            //Search All Favorite List with user id
            $result = Favorite::where('user_id', $id)->get();
            $favorites = json_decode($result, true);
            
            //StopList in Favorite List to return data
            $arrStopList = array();
            //BusList in Favorite List to return data
            $arrBusList = array();

            //Distinguish Data List between Stop and Bus
            foreach($favorites as $favorite){
                if($favorite['type'] === "S")
                    array_push($arrStopList, $favorite);
                else
                    array_push($arrBusList, $favorite);
            }

            //Create return data with StopList and BusList
            $arr = array('stopList' => $arrStopList, 'busList' => $arrBusList);
            return $arr;

        }catch(\Exception $e){

            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Delete Favorite items
        try{

            $favorite = Favorite::find($id);
            $favorite -> delete();
            
            return "Success";

        }catch(\Exception $e){
            
            return $e->getMessage();

        }
    }
}

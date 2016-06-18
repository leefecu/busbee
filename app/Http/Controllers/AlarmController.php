<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Alarm;

use Input;
use Redirect;

class AlarmController extends Controller
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
        return view('alarm.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Save Alarm List
        $user_id = Input::get('user_id');
        $stop_id = Input::get('stop_id');
        $route_id = Input::get('route_id');
        $short_name = Input::get('short_name');
        $on_off = Input::get('on_off');

        try{

            $alarm = new Alarm();
            $alarm -> user_id = $user_id;
            //Stop Code
            $alarm -> stop_id = $stop_id;
            //Bus Route Id
            $alarm -> route_id = $route_id;
            //Bus Number
            $alarm -> short_name = $short_name;
            //Alarm Setting status
            $alarm -> on_off = $on_off;
            $alarm->save();

            return "Success";

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

            //Search All Added Alarm List with user id
            $result = Alarm::where('user_id', $id)->get();
            $alarms = json_decode($result, true);

            return $alarms;

        }catch(\Exception $e){

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
        //
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
        //Update Alarm details (just on off status)
        
        $on_off = Input::get('on_off');

        try{

            $alarm = Alarm::find($id);
            //Alarm status
            $alarm -> on_off = $on_off;
            $alarm -> save();

            return "Success";

        }catch(\Exception $e){

            return $e->getMessage();

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Delete alarm 
        try{

            $alarm = Alarm::find($id);
            $alarm -> delete();

            return "Success";

        }catch(\Exception $e){

            return $e->getMessage();

        }
    }
}

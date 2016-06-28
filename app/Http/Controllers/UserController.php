<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;

use Input;
use Redirect;

class UserController extends Controller
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
        echo "User Create Page";
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //url: 'users' , method: 'POST', class: 'form'
        $name = Input::get('name');
        $email = Input::get('email');
        $passwd = Input::get('passwd');
        
        try{

            $user = new User();
            $user -> name = $name;
            $user -> email = $email;
            $user -> passwd = $passwd;
            $user -> save();

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
        //
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
        //
        try{

            $name = Input::get('name');
            $passwd = Input::get('passwd');

            $user = User::find($id);
            $user -> name = $name;
            $user -> passwd = $passwd;
            $user -> save();

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
        //
        try{

            $user = User::find($id);
            $user -> delete();
            
            return "Success";

        }catch(\Exception $e){
            
            return $e->getMessage();

        }

    }
}

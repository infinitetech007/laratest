<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use View;
use Auth;
use Hash;

class UsersController extends Controller
{
    /**
     * Display Users
     */
    public function index(Request $request)
    {
        try{
            if($request->ajax()){
                $result = $this->user;
                if(!empty($request->search))
                {
                    $result = $result->where('firstname','like','%'.$request->search.'%')
                                     ->orWhere('lastname','like','%'.$request->search.'%')
                                     ->orWhere('email','like','%'.$request->search.'%')
                                     ->orWhere('phone','like','%'.$request->search.'%');
                }
                $result = $result->paginate(20);
                $data = View::make('users.data', compact('result'))->render();
    
                return response()->json(['data' => $data]);
            }
            return view('users.index');
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Add & Update Users
     */
    public function addUsers(Request $request)
    {
        try{
            $message = "";  
            $addUsers = $this->user;
            $usersId = $request->usersid;
            $message = "user added successfully";
            if($usersId != null){
                $addUsers = $this->user::find($usersId);
                $message = "user updated successfully";
            }

            $addUsers->firstname = $request->firstname;
            $addUsers->lastname = $request->lastname;
            $addUsers->email = $request->email;
            $addUsers->phone = $request->phone;
            $addUsers->password = Hash::make($request->password);
            $addUsers->save();
            
            return redirect()->back()->with('message', $message);
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Delete Users
     */
    public function deleteUsers($id)
    {
        try{
            $id = decrypt($id);
            $users = $this->user::find($id)->delete();
            return [
                'status' => 200
            ];
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Edit Users
     */
    public function editUsers($id)
    {
        try{
            $id = decrypt($id);
            $data = $this->user::where('id', $id)->first();
            return [
                'status' => 'true',
                'data' => $data,     
            ];
        }
        catch(Exception $e){
            abort(500);
        }
    }
}

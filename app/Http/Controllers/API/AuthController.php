<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Validator;
use Hash;
use Auth;

class AuthController extends Controller
{
    /**
     *  Login
     */
    public function login(Request $request)
    {
        try{  
            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required'
            ]);
            
            if($validator->fails()) {
                return $this->sendError($validator->errors()->first(), [], 422);
            }
    
            $email = $request->email;
            $password = $request->password;
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('LaravelAuthApp')->accessToken;
                return $this->sendResponse($success, 'user login successfully');
            }

            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
        catch(Exception $e){
            return $this->sendError('something went wrong', 500);
        }
    }

    /**
     *  Register
     */
    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), [], 422);
            }
    
            $addUser = $this->user;
            $addUser->firstname = $request->firstname;
            $addUser->lastname = $request->lastname;
            $addUser->email = $request->email;
            $addUser->password = Hash::make($request->password);
            $addUser->save();
            
            return $this->sendResponse($addUser, 'user added successfully');
        }
        catch(Exception $e){
            return $this->sendError('something went wrong', 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * @param array 
     * 
     * @return array
     */

    public function register(Request $request){
        
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required'
            ]);
            
            //check for validation
            if ($validator->fails()) {
                return response()->json(["message" => $validator->errors()], 400);
            }

            $name = $request->name;
            $password = $request->password;
            $email = $request->email;

            User::create([
                'name' => $name,
                'password' => Hash::make($password),
                'email' => $email
            ]);
    
            return response()->json(["message" => "user successfully created"], 201);
       
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class ResetpasswordController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('validsession');
    }

    public function resetpassword(Request $request){

        try {

            $validator = FacadesValidator::make($request->all(),[
                'password' => 'required|confirmed'
            ]);

            //check fail validation
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'password does not match',
                ], 200);
            }
            
            //set token
            $this->setToken($request);
            
            //update password
            auth()->user()->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'you have successfully reset your password',
            ], 200);
    
            
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }
        
        
    }

    public function setToken($request){
        $bearer= $request->header('Authorization');
        $currentToken = explode(' ', $bearer)[1];

        $this->token = $currentToken;
    }


    public function getOtherSessions(){
        $currentToken = $this->token;

        $data = auth()->user()->sessions->pluck('token')->reject(function($token)use($currentToken){
            return $currentToken == $token;
        })->all();
        return $data;
    }

    public function deleteSession(){
        $sessions = $this->getOtherSessions();
        
        //iterate and delete session
        collect($sessions)->each(function($token){
            Session::where('token', $token)->delete();
        });

        return;
    }

    public function __destruct()
    {   
        $this->deleteSession();
    }


}

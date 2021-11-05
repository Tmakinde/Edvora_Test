<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class ResetpasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
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
            
            //update password
            auth()->user()->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'mesasge' => 'you have successfully reset your password',
            ], 200);
    
            
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }
        
        
    }


}

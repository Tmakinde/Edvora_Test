<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\AddSessionService;
use App\Models\Session;

class SessionController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('validsession');
    }

    public function terminate(Request $request){
        $this->setToken($request);

        $this->deleteSession();

        return response()->json([
            'message' => "terminated"
        ], 200);

    }

    public function setToken($request){
        $bearer= $request->header('Authorization');
        
        $currentToken = explode(' ', $bearer)[1];

        $this->token = $currentToken;
    }

    public function deleteSession(){       
        //delete session
        Session::where('token', $this->token)->delete();
        return;
    }

    
}

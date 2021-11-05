<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\AddSessionService;

class LoginController extends Controller
{
    protected $addSession;

    public function __construct(AddSessionService $addSessionService){
        $this->addSession = $addSessionService;
    }

    public function login(Request $request){
        try {

            $validator = Validator::make($request->all(),[
                'password' => 'required',
                'email' => 'required|exists:users'
            ]);

            //check fail validation
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                ], 200);
            }

            $credentials = request(['email', 'password']);

            //check if credential match
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            //fetch user
            $user = User::where('email', $request->email)->first();

            //add user session
            $this->addUserSession($user, $token);

            return $this->respondWithToken($token);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }



    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function addUserSession($user, $token){
        $this->addSession->saveSession($user, $token);
        return;
    }

    
}

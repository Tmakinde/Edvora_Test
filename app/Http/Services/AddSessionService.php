<?php
namespace App\Http\Services;
use App\Models\Session;

class AddSessionService
{

    public function saveSession($user, $token){
        //create session
        $session = Session::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        
        $user->sessions()->save(
            $session
        );

        return;
    }
}
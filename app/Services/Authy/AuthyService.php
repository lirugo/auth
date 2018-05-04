<?php

namespace App\Services\Authy;

use App\User;
use Authy\AuthyApi;
use Authy\AuthyFormatException;

class AuthyService
{
    private $client;

    public function __construct(AuthyApi $client)
    {
        $this->client = $client;
    }

    public function registerUser(User $user){
        $user = $this->client->registerUser(
            $user->email,
            $user->country_code,
            $user->phone
        );

        if(!$user->ok())
        {
            // throw ex
        }

        return $user->id();
    }

    public function verifyToken($token, User $user = null){
        try {
            $verification = $this->client->verifyToken(
                $user ? $user->authy_id : request()->session()->get('authy.authy_id'),
                $token
            );
        } catch (AuthyFormatException $e)
        {
//            trhrow ex
        }

        if(!$verification->ok())
        {
//            throw ex
        }

        return true;

    }

    public function requestSms(User $user){
        $request = $this->client->requestSms($user->authy_id, [
            'force' => true,
        ]);
        if(!$request->ok()){
//            throw ex
        }

    }
}
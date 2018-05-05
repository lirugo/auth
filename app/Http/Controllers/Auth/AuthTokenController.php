<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Authy;
use App\Services\Authy\Exceptions\SmsRequestFailedException;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Authy\Exceptions\InvalidTokenException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthTokenController extends Controller
{
    public function getToken(Request $request){
        if(!$request->session()->has('authy')){
            return redirect()->to('/');
        }
        return view('auth.token');
    }

    public function postToken(Request $request){
        //Validate
        $this->validate($request, [
            'token' => 'required'
        ]);
        //Check token
        try {
            $verification = Authy::verifyToken($request->token);
        } catch (InvalidTokenException $e){
            return redirect()->back()->withErrors([
                'token' => 'Invalid token'
            ]);
        }

        if(Auth::loginUsingId(
            $request->session()->get('authy.user_id'),
            $request->session()->get('authy.remember')
        )){
            return redirect()->intended();
        }

        return redirect()->url('/');
    }

    public function getResend(Request $request){
        $user = User::findOrFail($request->session()->get('authy.user_id'));

        if($user->hasSmsTwoFactorAuthenticationEnabled()){
            return redirect()->back();
        }

        try{
            Authy::requestSms($user);
        }catch (SmsRequestFailedException $e) {
            return redirect()->back();
        }

        return redirect()->back();
    }

}

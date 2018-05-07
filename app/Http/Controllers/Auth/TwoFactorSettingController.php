<?php

namespace App\Http\Controllers\Auth;

use App\DiallingCode;
use App\Facades\Authy;
use App\Services\Authy\Exceptions\RegistrationFailedException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TwoFactorSettingController extends Controller
{

    public function index(){
        return view('auth.twofactor')->with([
            'diallingCodes' => DiallingCode::all(),
        ]);
    }


    public function update(Request $request){
        //Validate
        $this->validate($request, [
           'two_factor_type' => 'required|in:'.implode(',', array_keys(config('twofactor.types'))),
           'phone' => 'required_unless:two_factor_type,off',
           'phone_number_dialling_code' => 'required_unless:two_factor_type,off'
        ]);
        //Get current user
        $user = $request->user();
        //Update data
        $user->updatePhoneNumber($request->phone, $request->phone_number_dialling_code);
        //Check authy_id
        if($user->registeredForTwoFactorAuthentication())
        {
            try{
                $authyId = Authy::registerUser($user);
                $user->authy_id = $authyId;
            }catch (RegistrationFailedException $e){
                return redirect()->back();
            }
        }
        $user->two_factor_type = $request->two_factor_type;
        $user->save();
        //Redirect back
        return redirect()->back();
    }
}

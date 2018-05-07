<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function phoneNumber(){
        return $this->hasOne(PhoneNumber::class);
    }

    public function hasTwoFactorAuthenticationEnabled(){
        return $this->two_factor_type !== 'off';
    }

    public function hasSmsTwoFactorAuthenticationEnabled(){
        return $this->two_factor_type !== 'sms';
    }

    public function hasTwoFactorType($type){
        return $this->two_factor_type === $type;
    }

    public function hasDiallingCode($diallingCodeId){
        if($this->hasPhoneNumber() === false){
            return false;
        }
        return $this->phoneNumber->diallingCode->id === $diallingCodeId;
    }

    public function hasPhoneNumber(){
        return $this->phoneNumber() == null;
    }

    public function updatePhoneNumber($phone, $phoneDiallingCode){
        $this->phoneNumber()->delete();

        return $this->phoneNumber()->create([
            'phone_number' => $phone,
            'dialling_code_id' => $phoneDiallingCode,
        ]);
    }

    public function registeredForTwoFactorAuthentication(){
        return $this->authy_id !== null;
    }

}

<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PendingLogin extends Model
{   
    const ATTEMPTCOUNT = 5;
    use HasFactory;

    public function requestLogin($data){
        
        
        $pendingLogin = static::where('mobile', $data->mobile)->first();

        if($pendingLogin == null){
            $pendingData = new PendingLogin();
            $pendingData->mobile = $data->mobile;
            $pendingData->otp = '111111';
            $pendingData->attemptCount = 1;
            $pendingData->attemptTime = Carbon::now();
            if($pendingData->save()){
                // send otp to mobile number 
            }

            return true;
        }



        if($pendingLogin->attemptCount > self::ATTEMPTCOUNT){
            $now = Carbon::now();
            if($pendingLogin->attemptTime < $now){
                // send otp to mobile number

                $pendingLogin->otp = '111111';
                $pendingLogin->attemptCount = 1;
                $pendingLogin->save();

                return true;
            }

            return 'Login Attempt Try after 1 hour!';
        }


        
        $pendingLogin->attemptCount = $pendingLogin->attemptCount + 1;
        $pendingLogin->attemptTime = ($pendingLogin->attemptCount > self::ATTEMPTCOUNT) ? Carbon::now()->add(1, 'hour') : Carbon::now(); 
        $pendingLogin->save();

        // send otp to mobile number
        // ...

        return true;
    }

    public function checkOtp($data){
        
        $pendingLogin = static::where('mobile', $data->mobile)->where('otp', $data->otp)->first();

        if($pendingLogin != null){
            return $pendingLogin;
        }

        return false;
    }


    public function deletePending($id){
        $pendingUser =  static::find($id);

        if($pendingUser->delete()){
            return true;
        }

        return false;
    }
}

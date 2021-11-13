<?php

namespace App\Repositories\SMSGateWay;

use App\Repositories\OrderItem\SMSGateWayInterface as OrderItemInterface;
// use App\Models\Order\OrderItem;
use Config;
use Artisan;
use Carbon\Carbon;

class SMSGateWayRepository implements SMSGateWayInterface
{
    public $baseUrl, $SMSAuthorization, $token;

    function __construct() {
	    $this->baseUrl = config('app.SMS_BASE_URL');
        $this->SMSAuthorization = config('app.SMSAuthorization');
        $this->token = config('app.smskey');
    }

    public function send($number, $message)
    {   
        $smsKeyGenTime = config('app.smsKeyGenTime');
        $savendays = new Carbon($smsKeyGenTime);
        $savendays = $savendays->addDays(7);
        $now = Carbon::now();
        if($savendays < $now){
            return $this->tokenGen();
        }
        // $this->tokenGen();
        return $this->sendSms('8801767404822', 'test');
    }


    public function tokenGen(){

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl.'/token?action=generate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic '.$this->SMSAuthorization
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);

        config(['app.smskey' => $response->token]);
        Artisan::call('cache:clear');


        // token enable 
        $this->tokenEnable($response->token);

        curl_close($curl);
        return $response;
    }

    public function tokenEnable($token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl.'/token?action=enable&token='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '.$this->SMSAuthorization
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


    public function sendSms($number, $message){
        $this->token = config('app.smskey');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://http.myvfirst.com/smpp/api/sendsms?to=8801767404822&from=8804445632712&text=hello-hi-by-by&dlr-mask=19&dlr-url',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJyb25nb2J1eWh0cGludCIsImV4cCI6MTYzNzU5NDY2MH0._oo_JCZbj-RlFKSsERUKAPoZK1Goge8T8BB5-FYHGGQ'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //$this->baseUrl.'?to='. $number .'&from=8804445632712&text='.$message.'&dlr-mask=19&dlr-url';
    }
}
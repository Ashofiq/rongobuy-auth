<?php 

namespace App\Repositories\SMSGateWay;


interface SMSGateWayInterface {

    public function send($number, $message);

    
}
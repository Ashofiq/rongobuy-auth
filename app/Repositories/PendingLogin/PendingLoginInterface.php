<?php 

namespace App\Repositories\PendingLogin;


interface PendingLoginInterface {

    public function requestLogin($data);


    public function checkOtp($data);


    public function deletePending($id);

}
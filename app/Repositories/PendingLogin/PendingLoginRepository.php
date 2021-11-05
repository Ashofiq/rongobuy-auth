<?php

namespace App\Repositories\PendingLogin;

use App\Repositories\PendingLogin\PendingLoginInterface as PendingLoginInterface;
use App\Models\User\PendingLogin;


class PendingLoginRepository implements PendingLoginInterface
{
    public $pendingLogin;

    function __construct(PendingLogin $pendingLogin) {
	    $this->pendingLogin = $pendingLogin;
    }

    public function requestLogin($data){
        return $this->pendingLogin->requestLogin($data);
    }

    public function checkOtp($data){
        return $this->pendingLogin->checkOtp($data);
    }

    public function deletePending($id){
        return $this->pendingLogin->deletePending($id);
    }
}
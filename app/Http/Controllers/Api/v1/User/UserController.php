<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserInterface;
use App\Helper\RespondsWithHttpStatus;
use Auth;
use Helper;

/** 
 * 
 */
class UserController extends Controller
{
    use RespondsWithHttpStatus;

    private $user;

    public function __construct(UserInterface $user){
        $this->user = $user;
    }

   
    public function allUser(Request $request){
        $final =$this->user->getAll();
        if($final){
           return $this->success('success',$this->user->getAll());
        }
        
        return $this->failure('message');

    }
    
    
    public function helper(Request $request){
        return $this->success('success', Helper::add());
    }

    public function me(){
        return $this->success('success', auth()->user());
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserTypes\UserTypeInterface;
use App\Repositories\PendingLogin\PendingLoginInterface;
use App\Repositories\SMSGateWay\SMSGateWayInterface;
use App\Models\UserTypes\UserTypes;
use App\Helper\RespondsWithHttpStatus;
use Helper;
use Validator;
use Hash;
/**
 * Class AuthController
 *
 * @author Ahmad shafik <ahmadshofiq3@gmail.com>
 *//**
 */
class AuthController extends Controller
{   
    use RespondsWithHttpStatus;
    private $user;
    private $userType, $pendingLogin, $sms;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(SMSGateWayInterface $sms, UserInterface $user, UserTypeInterface $userType, PendingLoginInterface $pendingLogin)
    {   
        $this->sms = $sms;
        $this->user = $user;
        $this->userType = $userType;
        $this->pendingLogin = $pendingLogin;
        $this->middleware('auth:api', ['except' => ['login', 'reg', 'me', 'sendOtp', 'checkOtp']]);
    }


    /**
     * RongoBuy Send OTP
     * 
     * @OA\Post(
     *     path="/api/auth/v1/send-otp",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=201,
     *         description="success"
     *     ),
    * @OA\RequestBody(
    *    required=true,
    *    description="Pass user credentials",
    *    @OA\JsonContent(
    *       required={"mobile"},
    *       @OA\Property(property="mobile", type="string", example="01767000000"),
    *    ),
    * ),
     *   
     *     
     * )
     */
    public function sendOtp(Request $request){
        return $this->sms->send('e', 'e');
        $validator = Validator::make($request->all(),[
            'mobile' => 'required'
        ]);

        if($validator->fails()){
            return $this->failure(Helper::VALIDATOR_FAIL_MESSAGE, Helper::validateErrorMsg($validator->errors()));
        }

        $data = Helper::removeDangerMultiple($request->all());
        // $data = 
        $sendOtp = $this->pendingLogin->requestLogin($data);

        if($sendOtp === true){
            return $this->success('OTP Send', '');
        }else{
            return $this->failure($sendOtp);
        }

        return $this->failure('Something Wrong');
        
    }

    /**
     * RongoBuy Check OTP
     * 
     * @OA\Post(
     *     path="/api/auth/v1/check-otp",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=201,
     *         description="success"
     *     ),
    * @OA\RequestBody(
    *    required=true,
    *    description="Check OTP",
    *    @OA\JsonContent(
    *       required={"mobile", "otp"},
    *       @OA\Property(property="mobile", type="string", example="01767000000"),
    *       @OA\Property(property="otp", type="string", example="111111"),
    *    ),
    * ),
     *   
     *     
     * )
     */
    public function checkOtp(Request $request){

        $validator = Validator::make($request->all(),[
            'mobile' => 'required',
            'otp'    => 'required'
        ]);

        if($validator->fails()){
            return $this->failure(Helper::VALIDATOR_FAIL_MESSAGE, Helper::validateErrorMsg($validator->errors()));
        }

        $data = Helper::removeDangerMultiple($request->all());

        $checkOtp = $this->pendingLogin->checkOtp($data);

        if($checkOtp === false){
            return $this->failure("Wrong OTP", 401);
        }

        // get user id
        $userId = $this->user->userIdByMobile($checkOtp->mobile);

        if($checkOtp != null){
            return $this->loginUsingId($userId, $checkOtp->id);
        }

        return $checkOtp; //$this->failure("Wrong OTP", 401);
    }

    /** @OA\Info(title="RongoBuy ecommerce", version="1.0") */

    
    public function login()
    {
        $credentials = request(['mobile', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->failure("Wrong mobile Or password", 401);
        }

        return $this->success('success', $this->respondWithToken($token));
    }

    
    public function reg($data)
    {   
        $final = $this->user->reg($data);
 
        if($final){

            if($createUserType){
                return $this->success('success', $final);
            }else{
                // delete this user 
            } 
        }
        
    }

    private function loginUsingId($id, $pendingId){

        if (! $token = auth()->tokenById($id)) {
            return $this->failure("Wrong OTP", 401);
        }

        // delete pending user
        $this->pendingLogin->deletePending($pendingId);

        return $this->success('success', $this->respondWithToken($token));

    }

    /**
     * Create User Type
     *
     * @return UserTypes
     */
    public function createUserType($userId, $type){

        $userTypeData = array(
            'user_id' => $userId,
            'type' => $type,
            'status' => UserTypes::ACTIVE
        );
        return $this->userType->addType($userTypeData);
    }

     /**
     * RongoBuy User
     * 
     * @OA\Get(
     *     path="/api/v1/me",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=201,
     *         description="success"
     *     ),
     *   security={{"bearer_token":{}}}
     *     
     * )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return array(
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        );
    }
}

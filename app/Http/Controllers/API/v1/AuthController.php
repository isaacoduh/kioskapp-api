<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResponseTrait;
    public $authRepository;
    public function __construct(AuthRepository $authRepo)
    {
        $this->middleware('auth:api', ['except' => ['login','register','verify_email']]);
        $this->authRepository = $authRepo;
    }

   

    public function register(Request $request)
    {
        try {
            $requestData = $request->only('name','email','password');
            $user = $this->authRepository->registerUser($requestData);

            if($user){
                \Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $user['email_verification_code']));
                if($token = $this->guard()->attempt($requestData)){
                    $data = $this->respondWithToken($token);
                    return $this->responseSuccess($data, 'User Account Created', Response::HTTP_OK);
                }

                // generate otp
                
            }
        }catch(\Exception $exception)
        {
            return $this->responseError(null, $exception->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email','password');
            if($token = $this->guard()->attempt($credentials)) {
                $data = $this->respondWithToken($token);
            } else {
                return $this->responseError(null, 'Invalid email or password', Response::HTTP_UNAUTHORIZED);
            }
            return $this->responseSuccess($data, 'Logged In successfully');
        } catch (\Exception $exception){
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            $this->guard()->logout();
            return $this->responseSuccess(null,'Logout successful');
        }catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     public function verify_email(Request $request) 
    {
        try {
            $data = User::where('email_verification_code', $request['otp'])->first();
            if(!$data) {
                return $this->responseError(null, 'Invalid Verification Operation');
            }
            $data['email_verified_at'] = now();
            $data['email_verification_code'] = null;
            $data->save();

            return $this->responseSuccess(null, 'Email Verification Successful');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function me()
    {
        try {
            $data = $this->guard()->user();
            return $this->responseSuccess($data, 'Profile Fetched!');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function refresh()
    {
        try {
            $data = $this->respondWithToken($this->guard()->refresh());
            return $this->responseSuccess($data, 'Token refresh successful');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60 * 24 * 30,
            'user' => $this->guard()->user()
        ];
        return $data;
    }

    public function guard($guard = 'api')
    {
        return Auth::guard($guard);
    }
}

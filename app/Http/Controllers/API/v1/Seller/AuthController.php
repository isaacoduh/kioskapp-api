<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
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
        $this->middleware('auth:seller-api',['except' => ['login','register']]);
        $this->authRepository = $authRepo;
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email','password');
            if($token = $this->guard('seller-api')->attempt($credentials)){
                $data = $this->respondWithToken($token);
            } else {
                return $this->responseError(null, 'Invalid email or password', Response::HTTP_UNAUTHORIZED);
            }
            return $this->responseSuccess($data, 'Logged In successfully');
        }catch (\Exception $exception){
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request)
    {
        try {
            $requestData = $request->only('name','email','password');
            $seller = $this->authRepository->registerSeller($requestData);

            if($seller){
                if($token = $this->guard('seller-api')->attempt($requestData)){

                    $data = $this->respondWithToken($token);
                    return $this->responseSuccess($data, 'Seller account created', Response::HTTP_OK);
                }
            }
        }catch (\Exception $exception){
            return $this->responseError(null, $exception->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            $this->guard('seller-api')->logout();
            return $this->responseSuccess(null,'Logout successful');
        }catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function me()
    {
        try {
            $data = $this->guard('seller-api')->user();
            return $this->responseSuccess($data, 'Profile Fetched!');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $data = $this->respondWithToken($this->guard('seller-api')->refresh());
            return $this->responseSuccess($data, 'Token refresh successful');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard('seller-api')->factory()->getTTL() * 60 * 24 * 30,
            'user' => $this->guard('seller-api')->user()
        ];

        return $data;
    }


    public function guard($guard)
    {
        return Auth::guard($guard);
    }

}

<?php

namespace App\Http\Controllers\API\v1\Admin;

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
        $this->middleware('auth:admin-api',['except' => ['login','register']]);
        $this->authRepository = $authRepo;
    }


    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email','password');
            if($token = $this->guard('admin-api')->attempt($credentials)){
                $data = $this->respondWithToken($token);
            } else {
                return $this->responseError(null, 'Invalid email or password', Response::HTTP_UNAUTHORIZED);
            }
            return $this->responseSuccess($data, 'Logged In successfully');
        }catch (\Exception $exception){
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            $this->guard('admin-api')->logout();
            return $this->responseSuccess(null,'Logout successful');
        }catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function me()
    {
        try {
            $data = $this->guard('admin-api')->user();
            return $this->responseSuccess($data, 'Profile Fetched!');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $data = $this->respondWithToken($this->guard('admin-api')->refresh());
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
            'expires_in' => $this->guard('admin-api')->factory()->getTTL() * 60 * 24 * 30,
            'user' => $this->guard('admin-api')->user()
        ];

        return $data;
    }


    public function guard($guard)
    {
        return Auth::guard($guard);
    }
}

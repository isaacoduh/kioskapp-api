<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


trait ResponseTrait
{
    public function responseSuccess($data = [], $message = "Successful", $status_code = JsonResponse::HTTP_OK)
    {
//        return response()->json([
//            'status' => true,
//            'message' => $message,
//            'errors' => null,
//            'data' => $data
//        ]. $status_code);
        return response()->json(['message' => $message,'status' => $status_code,'data' => $data], $status_code);
    }

    public function responseError($errors, $message = "Invalid Data", $status_code = JsonResponse::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => null,
        ], $status_code);
    }
}

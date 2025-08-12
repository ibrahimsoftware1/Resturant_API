<?php

namespace App\Trait;

trait ApiResponse
{
    public function ok($message,$data=[]){

        return $this->success($message,$data,200);
    }
    public function success($message,$data=[],$statusCode)
    {
        return response()->json([
            'message'=>$message,
            'data' => $data,
            'status'=>$statusCode,
        ],$statusCode);

    }
    public function error($errors,$statusCode){

        return response()->json([
            'errors' => $errors,
            'status' => $statusCode
        ], $statusCode);
    }
}

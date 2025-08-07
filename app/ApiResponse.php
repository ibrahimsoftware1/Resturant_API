<?php

namespace App;

trait ApiResponse
{
    public function ok($message,$data=[]){

        return $this->success($message,200);
    }
    public function success($message,$statusCode)
    {
        return response()->json([
            'message'=>$message,
            'status'=>$statusCode
        ],$statusCode);

    }
    public function error($errors,$statusCode){

        return response()->json([
            'errors' => $errors,
            'status' => $statusCode
        ], $statusCode);
    }
}

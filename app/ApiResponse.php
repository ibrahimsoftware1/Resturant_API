<?php

namespace App;

trait ApiResponse
{
    public function ok($message,$data=[]){

        return $this->success($message,$data,200);
    }
    public function success($message,$data,$statusCode)
    {
        return response()->json([
            'data'=>$data,
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

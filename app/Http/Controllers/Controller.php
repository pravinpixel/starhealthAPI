<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $error;

    public function returnError($errors = false, $message = 'Error', $code = 400 ,$statuscode ='')
    {
        return response([
            'success' => false,
            'message' => $message,
            'statuscode' => $statuscode,
            'error' => $errors
        ], $code);
    }
    public function returnSuccess($data, $message = 'Success')
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }
}

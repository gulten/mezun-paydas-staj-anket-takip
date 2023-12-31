<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function respond($data, $msg = null)
    {
        return ResponseBuilder::asSuccess()->withData($data)->withMessage($msg)->build();
    }

    public function respondWithMessage($msg)
    {
        return ResponseBuilder::asSuccess()->withMessage($msg)->build();
    }

    public function respondWithError($api_code, $http_code)
    {
        return ResponseBuilder::asError($api_code)->withHttpCode($http_code)->build();
    }

    public function respondBadRequest($api_code)
    {
        return $this->respondWithError($api_code, 400);
    }
    public function respondUnAuthorizedRequest($api_code)
    {
        return $this->respondWithError($api_code, 401);
    }
    public function respondNotFound($api_code)
    {
        return $this->respondWithError($api_code, 404);
    }
    //example
    //return $this->respondUnAuthorizedRequest(ApiCode::INVALID_CREDENTIALS);
}

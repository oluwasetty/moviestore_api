<?php

namespace App\Http\Controllers;

/**
* @OA\Info(
*      version="1.0.0",
*      title="Moviestore API",
*      description="This is the documentation for the moviestore API.",
*      @OA\Contact(
*          email="setty.095@gmail.com"
*      ),
*      @OA\License(
*          name="Apache 2.0",
*          url="http://www.apache.org/licenses/LICENSE-2.0.html"
*      )
* )
*
* @OA\Server(
*      url=L5_SWAGGER_CONST_HOST,
*      description="Moviestore API"
* )/**

 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token bearer Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 */

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

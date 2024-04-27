<?php

namespace App\Http\Controllers;

use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\Echo\Client\PHP\Socket\Socket;
use Elyerr\Passport\Connect\Traits\Passport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class GlobalController extends Controller
{
    use AuthorizesRequests, Socket, Passport, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    public function __construct()
    {
        $this->middleware('server');
    }
}

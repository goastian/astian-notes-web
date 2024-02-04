<?php

namespace App\Http\Controllers;
  
use Illuminate\Routing\Controller;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GlobalController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonResponser, Asset;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function AuthKey()
    {
        return request()->user()->id;
    }

    public function lowercase($value)
    {
        return strtolower($value);
    }

    public function uppercase($value)
    {
        return strtoupper($value);
    }
}

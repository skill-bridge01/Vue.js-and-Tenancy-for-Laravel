<?php

namespace App\EndPoints;
use App\Actions\Api;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Throwable;

class GetUserAction extends Api
{
    // public static function routes($router)
    // {
    //     $router->post('api/me',static::class);
    // }
    public function handle()
    {
        try {
            $user = Auth::user();
            // dd($user);
            return $user;
        } catch (Throwable $e) {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
        }
    }

    public function jsonResponse($data)
    {
        return response()->json($data);
    }
}
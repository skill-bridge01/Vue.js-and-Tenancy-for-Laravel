<?php

namespace App\EndPoints\Users;

use App\Actions\Api;
use App\Models\User;
use Throwable;

use Illuminate\Support\Facades\Route;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class GetUsersAction 
{
  use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/piece-list',static::class);
    // }

     /**
     * @OA\Get(
     *      path="/api/users",
     *      tags={"User"},
     *      summary="Get list of user",
     *      description="Returns list of user",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successfully get user list"
     *       ),
     *       @OA\Response(response=404, description="Not Found"),
     *     )
     *
     * Returns list of user
     */

    public function handle()
    {
      // return DB::connection('mysql')->table('pieces')->get();
      // tenancy()->central(function () {
      //   return Piece::all();
      // });
      return User::where('role','client')->get();
      // return User::all();
    }

 
    public function jsonResponse($service)
    {
        return $service;
    }
}

<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\Piece;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use DB;
class PieceList
{
  use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/piece-list',static::class);
    // }

    //  /**
    //  * @OA\Get(
    //  *      path="/api/pieces1",
    //  *      tags={"Piece1"},
    //  *      summary="Get list of piece",
    //  *      description="Returns list of piece",
    //  *      security={
    //  *           {"sanctum": {}}
    //  *      },
    //  *      @OA\Response(
    //  *          response=200,
    //  *          description="Successfully get piece list"
    //  *       ),
    //  *       @OA\Response(response=404, description="Not Found"),
    //  *     )
    //  *
    //  * Returns list of piece
    //  */

    public function handle()
    {
      // return DB::connection('mysql')->table('pieces')->get();
      // tenancy()->central(function () {
      //   return Piece::all();
      // });
      return Piece::with('services')->get();
    }

 
    public function jsonResponse($service)
{
    return $service;
}
}

<?php

namespace App\EndPoints\Prices;

use App\Actions\Api;
use App\Models\Service;
use App\Models\Service_piece;
use Throwable;

use Illuminate\Support\Facades\Route;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class GetPricesAction 
{
    use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/piece-list',static::class);
    // }

     /**
     * @OA\Get(
     *      path="/api/prices",
     *      tags={"Price"},
     *      summary="Get list of price",
     *      description="Returns list of price",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successfully get price list"
     *       ),
     *       @OA\Response(response=404, description="Not Found"),
     *     )
     *
     * Returns list of price
     */

    public function handle()
    {
      // return DB::connection('mysql')->table('pieces')->get();
      // tenancy()->central(function () {
      //   return Piece::all();
      // });
        // return Service_piece::all();
        // dd( Service_piece::with('pieceinfo')->get() );
    //   $service_piece = Service_piece::all();
    //   $service_piece = $service_piece->pieceinfo;
    //   return $service_piece;
      return Service_piece::with('serviceinfo')
            ->with('pieceinfo')
            ->get();
    }

 
    public function jsonResponse($service)
    {
        return $service;
    }
}

<?php

namespace App\EndPoints\Pieces;

use App\Actions\Api;
use App\Models\Piece;
use Throwable;

use Illuminate\Support\Facades\Route;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class GetPiecesAction 
{
    use AsAction, WithAttributes;

     /**
     * @OA\Get(
     *      path="/api/pieces",
     *      tags={"Piece"},
     *      summary="Get list of Piece",
     *      description="Returns list of piece",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successfully get piece list"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     *)
     *
     * Returns list of piece
     */


    public function handle()
    {
        try {
            //code...
            // return User::all();
            // return Piece::all();
            return Piece::with('services')->get();
            // return $piece;
          } catch (\Throwable $ex) {
            //throw $th;
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
          }

        
    }

 
    public function jsonResponse($service)
    {
        return $service;
    }
}

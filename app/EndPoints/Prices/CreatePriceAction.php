<?php
namespace App\EndPoints\Prices;

use App\Actions\Api;
use App\Models\Service_piece;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class CreatePriceAction {
  use AsAction, WithAttributes;

    /**
     * @OA\Post(
     *     path="/api/prices",
     *     tags={"Price"},
     *     summary="Create price",
     *     description="Create new price",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="service_id",
     *         in="query",
     *         description="Please enter serveice ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="piece_id",
     *         in="query",
     *         description="Please enter piece ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Please enter price",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Price created successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

  // public function handle($price = null) {

  //   try {
  //     //code...
  //     $data = $price ? : request();
      
  //     $price=Service_piece::where('service_id',$data['service_id'] )->where('piece_id',$data['piece_id'] )->get();
  //     if($price){
  //       dump('eee');
  //     } else{
  //       $price = new Service_piece();
  //       $price->service_id = $data['service_id'];
  //       $price->piece_id = $data['piece_id'];
  //       $price->price = $data['price'];
  //       $price->save();
  //       // dd($price);
  //       return $price;
  //     }
      
  //   } catch (\Throwable $ex) {
  //     //throw $th;
  //     env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
  //   }
  // }


  public function handle($priceData = null) {
    try {
        // If $priceData is not provided, use request data.
        $data = $priceData ?: request()->only('service_id', 'piece_id', 'price');

        // Ensure all necessary data exists
        if (!isset($data['service_id'], $data['piece_id'], $data['price'])) {
            // abort(400, "Missing required fields: service_id, piece_id, and/or price.");
            return ['valueError' => 'Please input correct values'];
        }

        // Attempt to find an existing price
        $existingPrice = Service_piece::where('service_id', $data['service_id'])
            ->where('piece_id', $data['piece_id'])
            ->first();

        if ($existingPrice) {
            // If price exists, you might want to do something, e.g., return or update it.
            // dump('eee'); // Placeholder action
            // return $existingPrice;
            return ['error' => 'Provided service and piece is already in use.'];
        } else {
            // Create and save a new price entry
            $newPrice = new Service_piece();
            $newPrice->service_id = $data['service_id'];
            $newPrice->piece_id = $data['piece_id'];
            $newPrice->price = $data['price'];
            $newPrice->save();
            return ['newPrice' => $newPrice,'success' => 1, 'message' => 'Price created successfully.'];
            // return $newPrice;
        }

    } catch (\Throwable $ex) {
        // Simplified error handling for demonstration
        return response()->json(['error' => env("APP_DEBUG") ? $ex->getMessage() : "Something went wrong."], 500);
    }
}

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

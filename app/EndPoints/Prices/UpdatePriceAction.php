<?php
namespace App\EndPoints\Prices;

use App\Actions\Api;
use App\Models\Service_piece;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class UpdatePriceAction {
  use AsAction, WithAttributes;

    /**
     * @OA\Put(
     *     path="/api/prices/{id}",
     *     tags={"Price"},
     *     summary="Edit price",
     *     description="Edit price by ID",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Please enter id of price",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *      @OA\Response(
     *          response=200,
     *          description="Price successfully updated"
     *       ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

  public function handle($id, $priceData = null) {

    try {
      //code...
      $data = $priceData ?: request();
      if (!isset($data['service_id'], $data['piece_id'], $data['price'])) {
        // abort(400, "Missing required fields: service_id, piece_id, and/or price.");
        return ['valueError' => 'Please input correct values'];
      } else{
        // dd('error', $id, $data->service_id, $data->piece_id);
        $existPair= Service_piece::where('id', '!=', $id)->where('service_id', $data->service_id)->where('piece_id', $data->piece_id)->first();
        if($existPair){
          return ['error' => 'The service and piece pair provided is already in use'];
        } else {
          $service = Service_piece::find($id);
          $service->service_id = $data->service_id;
          $service->piece_id = $data->piece_id;
          $service->price = $data->price;
          $service->save();
          return ['service' => $service,'success' => 1, 'message' => 'Price updated successfully.'];
        }
      }
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

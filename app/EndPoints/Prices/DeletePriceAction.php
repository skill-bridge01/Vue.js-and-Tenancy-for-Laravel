<?php
namespace App\EndPoints\Prices;

use App\Actions\Api;
use App\Models\Service_piece;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class DeletePriceAction {
  use AsAction, WithAttributes;

  /**
     * @OA\Delete(
     *      path="/api/prices/{id}",
     *      tags={"Price"},
     *      summary="Delete price",
     *      description="Delete price by ID",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Please enter id price",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Price successfully removed"
     *       ),
     *       @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     *)
     *
     * Delete specific price
     */

  public function handle($id, $serviceInfo = null) {

    try {
      //code...
      $service = Service_piece::find($id);
      $service->delete();
      return $service;
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

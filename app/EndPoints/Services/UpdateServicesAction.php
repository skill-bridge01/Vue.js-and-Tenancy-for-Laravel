<?php
namespace App\Endpoints\Services;

use App\Actions\Api;
use App\Models\Service;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class UpdateServicesAction {
  use AsAction, WithAttributes;

  /**
   * @OA\Put(
   *     path="/api/services/{id}",
   *     tags={"Service"},
   *     summary="Edit service",
   *     description="Edit service by ID",
   *     security={
   *          {"bearerAuth": {}}
   *     },
   *     @OA\Parameter(
   *          name="id",
   *          in="path",
   *          description="Please enter id service",
   *          required=true,
   *          @OA\Schema(
   *              type="integer"
   *          )
   *      ),
   *      @OA\Parameter(
   *         name="title",
   *         in="query",
   *         description="Please enter service name",
   *         required=true,
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ),
   *     @OA\Parameter(
   *         name="is_checked",
   *         in="query",
   *         description="Please enter 1 OR 0",
   *         required=true,
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ),
   *    @OA\Parameter(
   *         name="is_shown",
   *         in="query",
   *         description="Please enter 1 OR 0",
   *         required=true,
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ),
   *      @OA\Response(
   *          response=200,
   *          description="Service successfully updated"
   *       ),
   *     @OA\Response(
   *         response=400,
   *         description="Bad Request"
   *     ),
   * )
   */

  public function handle($id, $serviceInfo = null) {

    try {
      //code...
      $data = $serviceInfo ? : request();
      $service = Service::find($id);
      // ->update(['piece_title' => $data->title]);
      $service->services_title = $data->title;
      $service->is_checked = $data->is_checked;
      $service->is_shown = $data->is_shown;
      $service->save();
      // return $service;
      return ['service' => $service,'success' => 1, 'message' => 'Service updated successfully.'];
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

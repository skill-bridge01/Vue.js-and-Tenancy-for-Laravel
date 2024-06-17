<?php
namespace App\Endpoints\Services;

use App\Actions\Api;
use App\Models\Service;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class CreateServiceAction {
  use AsAction, WithAttributes;

    /**
     * @OA\Post(
     *     path="/api/services",
     *     tags={"Service"},
     *     summary="Create service",
     *     description="Create a new service",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="services_title",
     *         in="query",
     *         description="Please enter a service name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="is_checked",
     *         in="query",
     *         description="Please enter 1 or 0",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="is_shown",
     *         in="query",
     *         description="Please enter 1 or 0",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Service created successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

  public function handle($service = null) {

    try {
      //code...
      $data = $service ? : request();
      // $service = new Service();
      // $service->services_title = $data['services_title'];
      // $service->is_checked = $data['is_checked'];
      // $service->is_shown = $data['is_shown'];
      // $service->save();
      // // dd($service);
      // return $service;
      $existingService = Service::where('services_title', $data['services_title'])->first();
      // dd($existingService);

      if ($existingService) {
          return ['error' => 'Provided Service is already in use.'];
      } else {
        $service = new Service();
        $service->services_title = $data['services_title'];
        $service->is_checked = $data['is_checked'];
        $service->is_shown = $data['is_shown'];
        $service->save();
        // event(new Registered($piece));
        return ['service' => $service,'success' => 1, 'message' => 'Service created successfully.'];      }
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

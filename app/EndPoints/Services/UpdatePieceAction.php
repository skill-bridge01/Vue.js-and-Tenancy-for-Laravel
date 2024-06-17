<?php
namespace App\Endpoints\Services;

use App\Actions\Api;
use App\Models\Service;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class UpdateServiceAction {
  use AsAction, WithAttributes;

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

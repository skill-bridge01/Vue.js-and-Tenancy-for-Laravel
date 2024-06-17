<?php
namespace App\Endpoints;

use App\Actions\Api;
use App\Models\User;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Hash;

class EmailVerification {
  use AsAction, WithAttributes;

  public function handle($id, $hash) {
    try {
      //code...
      $service = User::find($id);
      $hash1 = sha1($service->email);
      if (sha1($service->email)===$hash){
        $service->email_verified_at = now();
        $service->save();
        // return $service;
        return redirect('/');
      }
    //   $service->email_verified_at = now();
    //   dd("kkk", $id, $hash, now(), $service->email_verified_at, $service->email);
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use DB;
class PhoneNumberVerifyAction
{
  use AsAction, WithAttributes;
    


    public function handle($invoceinfo = null)
    {
      try {
        //code...
        
        $data = $invoceinfo ? : request();
        // dd($data);
        $user = User::find(1);
        if($user->verification_code===$data['phone']){
          $user->phone_verified_at=now();
          $user->save();
          return ['success' => 'Phone verify success'];
        } else {
          return ['error' => 'Please input correct code'];
        }
        // ->update(['piece_title' => $data->title]);
        // $user->services_title = $data->phone;
        // $user->is_checked = $data->is_checked;
        // $user->is_shown = $data->is_shown;
        // $user->save();
        // return $service;
       
      } catch (\Throwable $ex) {
        //throw $th;
        env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
      }
    }

 
    public function jsonResponse($data)
    {
      return response()->json($data);
    }
}

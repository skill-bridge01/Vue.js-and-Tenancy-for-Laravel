<?php

namespace App\EndPoints\Services;

use App\Actions\Api;
use App\Models\Service;
use Throwable;

use Illuminate\Support\Facades\Route;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class GetServicesAction 
{
    use AsAction, WithAttributes;

     /**
     * @OA\Get(
     *      path="/api/services",
     *      tags={"Service"},
     *      summary="Get list of Service",
     *      description="Returns list of service",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successfully get service list"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     *)
     *
     * Returns list of service
     */
   
    public function handle()
    {
      return Service::all();
    }

 
    public function jsonResponse($service)
    {
        return $service;
    }
}

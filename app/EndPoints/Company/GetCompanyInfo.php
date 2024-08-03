<?php

namespace App\EndPoints\Company;

use App\Actions\Api;
use App\Models\Piece;
use App\Models\Company;
use Throwable;

use Illuminate\Support\Facades\Route;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class GetCompanyInfo 
{
    use AsAction, WithAttributes;

    public function handle()
    {
        try {
            return Company::all();
          } catch (\Throwable $ex) {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
          }

        
    }

 
    public function jsonResponse($service)
    {
        return $service;
    }
}

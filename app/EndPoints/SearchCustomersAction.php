<?php

namespace App\EndPoints;

use App\Actions\Api;
// use App\Actions\SendInvoicViaWhatsapp;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

use Throwable;

class SearchCustomersAction
{
    use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/search-customer',static::class);
    // }

    /**
     * @OA\Post(
     *     path="/api/search-customer",
     *     tags={"Customer"},
     *     summary="Search Customer",
     *     description="Search Customer by keyword",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Please enter keyword",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Search successfully"
     *     ),
     *     
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

    public function handle($query="")
    {
        try{
            $query=$query?:request()->keyword;
            if($query!="")
            $customers = Customer::where('name', 'like', '%' . $query . '%')
            ->orWhere('mobile', 'like', '%' . $query . '%')
            ->get();
            else
            $customers = Customer::all();           

            return $customers;
        }
        catch(Throwable $ex)
        {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
        }

    }

 
    public function jsonResponse($customer)
{
   
    return ["customer"=>$customer,"status"=>true];
 
}
}

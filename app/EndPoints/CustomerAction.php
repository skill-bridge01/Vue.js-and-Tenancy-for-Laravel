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

class CustomerAction
{
    use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/create-customer',static::class);
    // }

    public function rules()
    {
        return [
            'name' => 'required',
            'mobile' => 'required|unique:customers|digits:10',
          //  'email' => 'required|email|unique:customers',
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/create-customer",
     *     tags={"Customer"},
     *     summary="Create Customer",
     *     description="Create new Customer",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Please enter full name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         description="Please enter mobile",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Customer created successfully"
     *     ),
     *     
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

    public function handle($info=null)
    {
        try{
            $data = $info?:request();
            $customer = new Customer();
            $customer->name = $data['name'];
            $customer->email = $data['email'];
            $customer->mobile = $data['mobile'];
            $customer->save();
            return $customer ;
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

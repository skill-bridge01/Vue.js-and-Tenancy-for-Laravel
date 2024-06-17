<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class UpdateInvoice 
{
    use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/update-invoice',static::class);
    // }

    public function rules()
    {
        return [
            'status'=>['required'],
            'id'=>['required']
        ];
    }

    /**
   * @OA\Post(
   *     path="/api/update-invoice",
   *     tags={"Invoice"},
   *     summary="Edit invoice",
   *     description="Edit invoice by ID",
   *     security={
   *          {"bearerAuth": {}}
   *     },
   *     @OA\Parameter(
   *          name="id",
   *          in="query",
   *          description="Please enter invoice id",
   *          required=true,
   *          @OA\Schema(
   *              type="integer"
   *          )
   *      ),
   *      @OA\Parameter(
   *         name="status",
   *         in="query",
   *         description="Please enter Status",
   *         required=true,
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ),
   *      @OA\Response(
   *          response=200,
   *          description="Invoice successfully updated"
   *       ),
   *     @OA\Response(
   *         response=400,
   *         description="Bad Request"
   *     ),
   * )
   */

    public function handle($invoceinfo = null)
    {
        try{
            $invoceinfo = $invoceinfo?:request();
            $invoice = Invoice::where('id', $invoceinfo->id)->firstOrFail();
            $invoice->status =$invoceinfo->status;
            $invoice->save();
            return $invoice ;
        }
        catch(Throwable $ex)
        {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
        }

    }

 
    public function jsonResponse($invoice)
    {
    
        return ["invoice"=>$invoice,"status"=>true];
    
    }
}

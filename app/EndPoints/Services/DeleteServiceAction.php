<?php
namespace App\Endpoints\Services;

use App\Actions\Api;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Service_piece;
use App\Models\Service_piece_invoices;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class DeleteServiceAction {
  use AsAction, WithAttributes;

  /**
   * @OA\Delete(
   *      path="/api/services/{id}",
   *      tags={"Service"},
   *      summary="Delete service",
   *      description="Delete service by ID",
   *      security={
   *           {"bearerAuth": {}}
   *      },
   *      @OA\Parameter(
   *          name="id",
   *          in="path",
   *          description="Please enter ID of service",
   *          required=true,
   *          @OA\Schema(
   *              type="integer"
   *          )
   *      ),
   *      @OA\Response(
   *          response=200,
   *          description="Piece successfully removed"
   *       ),
   *       @OA\Response(
   *         response=500,
   *         description="Internal Server Error"
   *     ),
   *)
    *
    * Delete specific piece
    */

  public function handle($id, $serviceInfo = null) {

    try {
      //code...
      $service = Service::find($id);

      $service_pieces = Service_piece::where('service_id', $service->id)->get();
      foreach ($service_pieces as $service_piece) {
        $service_piece_invoices=Service_piece_invoices::where('service_piece_id', $service_piece->id)->get();
        foreach ($service_piece_invoices as $service_piece_invoice) {
          $service_piece_invoice->delete();
        }
        foreach ($service_piece_invoices as $service_piece_invoice) {
          $invoice = Invoice::find($service_piece_invoice->invoice_id);
          if ($invoice) {
              $invoice->delete();
          }
        }
      }

      foreach ($service_pieces as $service_piece) {
        $service_piece->delete();
      }

      $service->delete();
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

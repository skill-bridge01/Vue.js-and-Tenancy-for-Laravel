<?php
namespace App\Endpoints\Pieces;

use App\Actions\Api;
use App\Models\Piece;
use App\Models\Invoice;
use App\Models\Service_piece;
use App\Models\Service_piece_invoices;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class DeletePieceAction {
  use AsAction, WithAttributes;

  /**
   * @OA\Delete(
   *      path="/api/pieces/{id}",
   *      tags={"Piece"},
   *      summary="Delete piece",
   *      description="Delete piece by ID",
   *      security={{"bearerAuth":{}}},
   *      @OA\Parameter(
   *          name="id",
   *          in="path",
   *          description="Please enter id piece",
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

  public function handle($id, $pieceInfo = null) {

    try {
      //code...
      $piece = Piece::find($id);
      $service_pieces = Service_piece::where('piece_id', $piece->id)->get();
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
      $piece->delete();
      return $piece;
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

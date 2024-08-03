<?php
namespace App\Endpoints\Pieces;

use App\Actions\Api;
use App\Models\Piece;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class UpdatePieceAction {
  use AsAction, WithAttributes;

  /**
   * @OA\Put(
   *     path="/api/pieces/{id}",
   *     tags={"Piece"},
   *     summary="Edit piece",
   *     description="Edit piece by ID",
   *     security={
   *          {"bearerAuth": {}}
   *     },
   *     @OA\Parameter(
   *          name="id",
   *          in="path",
   *          description="Please enter id piece",
   *          required=true,
   *          @OA\Schema(
   *              type="integer"
   *          )
   *      ),
   *      @OA\Parameter(
   *         name="title",
   *         in="query",
   *         description="Please enter piece name",
   *         required=true,
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ),
   *      @OA\Response(
   *          response=200,
   *          description="Piece successfully updated"
   *       ),
   *     @OA\Response(
   *         response=400,
   *         description="Bad Request"
   *     ),
   * )
   */

  public function handle($id, $piece = null) {

    try {
      //code...
      $data = $piece ? : request();
      $piece = Piece::find($id);

      if ($data === null) {
        // Handle the case when $request is null. Perhaps log an error or throw an exception.
        throw new \Exception("Request is null.");
      }
      // dd($data);
      
      // $existingPiece = Piece::where('piece_title', $data['title'])->first();
      // if ($existingPiece) {
      //     return ['error' => 'Provided Piece is already in use.'];
      // } else {
        if ($data->hasFile('image')) {
          $imagePath = $data->file('image')->store('public/uploads/piece');
          $imagePath = str_replace('public/', '', $imagePath);
          $piece->piece_title = $data['title'];
          $piece->image = $imagePath;
          $piece->save();
          return ['piece' => $piece,'success' => 1, 'message' => 'Piece updated successfully.'];
        } else {
          // $imagePath = null;
          $piece->piece_title = $data['title'];
          $piece->save();
          return ['piece' => $piece,'success' => 1, 'message' => 'Piece updated successfully.'];
        }
      // }
      // ->update(['piece_title' => $data->title]);
      // $piece->piece_title = $data->title;
      // $piece->save();
      // return $piece;
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

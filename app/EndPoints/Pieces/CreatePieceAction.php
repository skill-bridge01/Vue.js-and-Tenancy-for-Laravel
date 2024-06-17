<?php
namespace App\Endpoints\Pieces;

use App\Actions\Api;
use App\Models\Piece;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class CreatePieceAction {
  use AsAction, WithAttributes;

    /**
     * @OA\Post(
     *     path="/api/pieces",
     *     tags={"Piece"},
     *     summary="Create Piece",
     *     description="Create a new piece",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Please enter a piece name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Piece created successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

  public function handle($piece = null) {

    try {
      //code...
      // $data = $piece ? : request();
      // $piece = new Piece();
      // $piece->piece_title = $data['title'];
      // $piece->save();
      // return $piece;

      $data = $piece ? : request();
      
      $existingUser = Piece::where('piece_title', $data['title'])->first();
      // dd($existingUser);

      if ($existingUser) {
          return ['error' => 'Provided Piece is already in use.'];
      } else {
        $piece = new Piece();
        $piece->piece_title = $data['title'];
        $piece->save();
        // event(new Registered($piece));
        return ['piece' => $piece,'success' => 1, 'message' => 'Piece created successfully.'];
      }
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

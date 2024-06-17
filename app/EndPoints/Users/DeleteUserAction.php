<?php
namespace App\Endpoints\Users;

use App\Actions\Api;
use App\Models\User;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class DeleteUserAction {
  use AsAction, WithAttributes;
    /**
     * @OA\Delete(
     *      path="/api/users/{id}",
     *      tags={"User"},
     *      summary="Delete user",
     *      description="Delete user by ID",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Please enter id user",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully removed"
     *       ),
     *       @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     *)
     *
     * Delete specific user
     */

  public function handle($id, $userInfo = null) {

    try {
      //code...
      $user = User::find($id);
      $user->delete();
      return $user;
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

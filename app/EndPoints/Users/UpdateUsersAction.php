<?php
namespace App\Endpoints\Users;

use App\Actions\Api;
use App\Models\User;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UpdateUsersAction {
  use AsAction, WithAttributes;

  public function rules()
    {
        return [
            'oldPassword' => 'required|min:8',
            'password' => 'required|min:8',
        ];
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"User"},
     *     summary="Edit user",
     *     description="Edit user",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Please enter id user",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="oldPassword",
     *         in="query",
     *         description="Please enter oldPassword",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Please enter new Password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully updated"
     *       ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */


  public function handle($id, $userInfo = null) {

    try {
      //code...
      $data = $userInfo ? : request();
      $user = User::find($id);
      // dd($data['oldPassword']);
      if (!Hash::check($data['oldPassword'], $user->password)) {
          return ['error' => 'The provided password does not match our records.'];
      }

      // Update the password
      $user->password = Hash::make($data['password']);
      $user->name=$data['newUsername'];
      $user->username=$data['newUsername'];
      $user->save();
      event(new Registered($user));
      return ['user' => $user,'success' => 1, 'message' => 'Password updated successfully.'];
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

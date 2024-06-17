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
class CreateUserAction {
  use AsAction, WithAttributes;

  public function rules()
    {
        return [
            // 'email' => 'required|email|unique:users',
            'username' => 'required|min:6|unique:tenants,id',
            'password' => 'required|min:8|confirmed',
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Create user",
     *     description="Create new user",
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Please enter your full name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Please enter your email ",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Please enter your password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="User created successfully"
     *     ),
     *     
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */
  

  public function handle($user = null) {

    try {
      // $data = $user ? : request();
      // $user = new User();
      // $user->email = $data['email'];
      // $user->username = $data['username'];
      // $user->name = $data['username'];
      // $user->password = Hash::make($data['password']);
      // $user->role='client';
      // $user->save();
      // event(new Registered($user));
      // return $user;

      // $data = $user ? : request()->only('username', 'password');
      $data = $user ? : request();

      // if (!isset($data['username'], $data['password'])) {
      //   // abort(400, "Missing required fields: service_id, piece_id, and/or price.");
      //   return ['error' => 'Please input correct values'];
      // }
      
      if($data['email']){
        $existingUser = User::where('email', $data['email'])
        // ->where('username', $data['username'])
        ->orWhere('username', $data['username'])
        ->first();
      } else {
        $existingUser = User::where('username', $data['username'])->first();
      }
      

      if ($existingUser) {
          return ['error' => 'Provided User exist.'];
      } else {
        $user = new User();
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->name = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->role='client';
        $user->save();
        event(new Registered($user));
        return ['user' => $user,'success' => 1, 'message' => 'User created successfully.'];      }
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

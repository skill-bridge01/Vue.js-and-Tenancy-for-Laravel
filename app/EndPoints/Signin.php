<?php

namespace App\EndPoints;

use App\Actions\Api;
// use App\Actions\SendInvoicViaWhatsapp;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use Throwable;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\ValidationException;
class Signin
{
    use AsAction, WithAttributes;
    // public static function routes($router)
    // {
    //     $router->post('api/signin',static::class);
    // }

    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
            // 'device_name' => 'required'
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/signin",
     *     tags={"Login"},
     *     summary="Login",
     *     description="Signin",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Please enter your email",
     *         required=true,
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
     *       @OA\Parameter(
     *         name="device_name",
     *         in="query",
     *         description="Please enter your device name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Login success"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */
    
    public function handle($userinfo = null)
    {
        try{
            // $data=$invoceinfo?:request();
            $data = $userinfo ? : request();
            // dd($data['email']);

            // Tenant create
            $user = User::where('email', $data['email'])
                ->orWhere('username', $data['email'])
                ->first();
            
            if (! $user || ! Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([ 
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            // return $user->createToken($request->device_name)->plainTextToken;
            // return $user, $user->createToken($request->device_name)->plainTextToken;
            // if (!$user) {
            //     return response()->json(['message' => 'User not found'], 404);
            // }

            // Assure 'device_name' is provided
            // $user->validate(['device_name' => 'required']);

            // Create token
            $token = $user->createToken($data['device_name'])->plainTextToken;
            // dd($token);
            return ['user' => $user, 'token' => $token];
            // return $user;

            // return response()->json(['user' => $user, 'token' => $token]);
        }
        catch(Throwable $ex)
   {
    env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
   }

    }

 
    public function jsonResponse($user)
    {
    
        return ["data"=>$user,"status"=>true];
    
    }

    public function asController(ActionRequest $request)
    {
        $this->fillFromRequest($request);
        $this->validateAttributes();
        return $this->handle();
    }
}

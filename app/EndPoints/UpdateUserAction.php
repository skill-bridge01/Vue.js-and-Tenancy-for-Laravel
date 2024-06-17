<?php

namespace App\EndPoints;
use App\Actions\Api;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class UpdateUserAction
{
    use AsAction, WithAttributes;
    // public static function routes($router)
    // {
    //     $router->post('api/me',static::class);
    // }

    public function rules()
    {
        return [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/update-password",
     *     tags={"Setting"},
     *     summary="Change Password",
     *     description="Change Password",
     *     security={
     *          {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="old_password",
     *         in="query",
     *         description="Please enter Old Password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="new_password",
     *         in="query",
     *         description="Please enter New Password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Password successfully changed"
     *       ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

    public function handle($userInfo = null)
    {
        $data = $userInfo? : request();
        // dd($data);
        try {
            $user = Auth::user();
            // dd($user);
            // Check if the old password matches the current password
            if (!Hash::check($data['old_password'], $user->password)) {
                return ['error' => 'The provided password does not match our records.'];
            }

            // Update the password
            $user->password = Hash::make($data['new_password']);
            $user->save();
            return ['success' => 1, 'message' => 'Password updated successfully.'];
        } catch (Throwable $ex) {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
        }
    }

    public function jsonResponse($data)
    {
        return response()->json($data);
    }
}
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
use Twilio\Rest\Client;
use Throwable;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;
class CreateUserAction
{
    use AsAction, WithAttributes;
    // public static function routes($router)
    // {
    //     $router->post('api/signup',static::class);
    // }

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'username' => 'required|unique:tenants,id',
            'password' => 'required|min:8|confirmed',
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/signup",
     *     tags={"Register"},
     *     summary="Register",
     *     description="Register",
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
     *         name="name",
     *         in="query",
     *         description="Please enter your name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Please enter your Username",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Please enter your phone number",
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
     *     @OA\Response(
     *          response=201,
     *          description="Register success"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

    public function handle($invoceinfo = null)
    {
        try{
            $data=$invoceinfo?:request();

            // Tenant create
            $tenant = Tenant::create(['id' => $data['username']]);
            $tenant->domains()->create(['domain' => $data['username'].'.'.env('APP_DOMAIN_NAME')]);

            $tenant->run(function () use ($data) {
                \Log::warning('new user  could be created.');

                $code = mt_rand(100000, 999999);
                $user = new User();
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                $user->verification_code = $code;
                $user->password = Hash::make($data['password']);
                $user->username=$data['username'];
                $user->role='admin';
                // $user->phone_verified_at=now(); // this line should be removed after integrate with sms service provider

                $user->save();



                $twilioSid = env('TWILIO_SID');
                $twilioToken = env('TWILIO_AUTH_TOKEN');
                $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
                // $recipientNumber = '+'.$data['phone'];
                // dd($recipientNumber);
                $recipientNumber = '+447893928874'; // Replace with the recipient's phone number in WhatsApp format (e.g., "whatsapp:+1234567890")
                $message = "Hello from Twilio WhatsApp API in Laravel! 🚀";

                $twilio = new Client($twilioSid, $twilioToken);
                // dd('kkkk2');
                // dd($code);
                try {
                    $twilio->messages->create(
                        $recipientNumber,
                        [
                            "from" => $twilioWhatsAppNumber,
                            "body" => $code." is your verification code for Laundry",
                        ]
                    );

                    return response()->json(['message' => $code]);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }



                event(new Registered($user));
                return User::find($user->id);
            });
            // $user = new User();
            // $user->name = $data['name'];
            // $user->email = $data['email'];
            // $user->phone = $data['phone'];
            // $user->password = Hash::make($data['password']);
            // $user->username=$data['username'];
            // $user->save();
            return $tenant;
        }
        catch(Throwable $ex)
   {
    env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
   }

    }


    public function jsonResponse($user)
    {

        return ["user"=>$user,"status"=>true];

    }

    public function asController(ActionRequest $request)
    {
        $this->fillFromRequest($request);
        $this->validateAttributes();
        return $this->handle();
    }
}

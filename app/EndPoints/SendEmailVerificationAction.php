<?php

namespace App\EndPoints;

use App\Actions\Api;
// use App\Actions\SendInvoicViaWhatsapp;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

use Throwable;

class SendEmailVerificationAction extends Api
{
    
    public static function routes($router)
    {
        $router->post('api/send-verification-email',static::class);
    }


    public function handle($user=null)
    {
        try{
            $user=$user?:User::where('id',request()->user_id)->firstOrfail();
            // $verificationUrl = URL::temporarySignedRoute(
            //     'verification.verify',
            //     now()->addMinutes(60),
            //     ['id' => $user->id]
            // );
    
            // Mail::send('emails.verify', ['user' => $user, 'verificationUrl' => $verificationUrl], function ($message) use ($user) {
            //     $message->to($user->email)->subject('Email Verification');
            // });
    
            // Handle the email verification response as needed
        }
        catch(Throwable $ex)
   {
    env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
   }

    }

 
    public function jsonResponse()
{
   
    return ["msg"=>"Verification email has been sent successfully","status"=>true];
 
}
}

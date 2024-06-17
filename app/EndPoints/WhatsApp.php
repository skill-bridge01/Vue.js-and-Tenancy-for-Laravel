<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\Piece;
use App\Models\Service;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Route;
use DB;
class WhatsApp extends Api
{
    
    // public static function routes($router)
    // {
    //     $router->post('api/piece-list',static::class);
    // }
    public function handle()
    {
      dd("sdfsd");
      $twilioSid = config('app.twilio_sid');
      $twilioToken = config('app.twilio_auth_token');
      $twilioWhatsAppNumber = config('app.twilio_whatsapp_number');
      $recipientNumber = '13156365323'; // Replace with the recipient's phone number in WhatsApp format (e.g., "whatsapp:+1234567890")
      $message = "Hello from Twilio WhatsApp API in Laravel! 🚀";

      $twilio = new Client($twilioSid, $twilioToken);
      dd($twillo);
      try {
          $twilio->messages->create(
              $recipientNumber,
              [
                  "from" => $twilioWhatsAppNumber,
                  "body" => $message,
              ]
          );

          return response()->json(['message' => 'WhatsApp message sent successfully']);
      } catch (\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
      }
    }

 
    public function jsonResponse($service)
{
    return $service;
}
}

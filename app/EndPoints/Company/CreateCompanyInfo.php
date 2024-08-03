<?php
namespace App\Endpoints\Company;

use App\Actions\Api;
use App\Models\Company;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Http\Request;

class CreateCompanyInfo {
  use AsAction, WithAttributes;

  // public function handle($request, $company= null) {
    public function handle(Request $request) {

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'profile_picture' => 'required|file|image|max:2048', // Example validation rules
    ]);

    $data = $request ?: request();
    if ($request === null) {
        throw new \Exception("Request is null.");
    }
    try {
      if ($request->hasFile('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('public/uploads');
        $profilePicturePath = str_replace('public/', '', $profilePicturePath);
      } else {
        $profilePicturePath = null;
      }
      $user = Company::create([
        'name' => $validated['name'],
        'address' => $validated['address'],
        'logo' => $profilePicturePath,
        'commercial_number' => $data['commercialNumber'],
        'tax_number' => $data['taxNumber'],
      ]);
      return response()->json($user, 201);
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

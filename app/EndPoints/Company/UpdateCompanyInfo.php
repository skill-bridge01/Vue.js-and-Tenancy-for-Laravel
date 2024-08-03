<?php
namespace App\Endpoints\Company;

use App\Actions\Api;
use App\Models\Piece;
use App\Models\Company;
use Throwable;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class UpdateCompanyInfo {
  use AsAction, WithAttributes;

  public function handle(Request $request) {

    if ($request === null) {
      throw new \Exception("Request is null.");
    }

    try {
      //code...
      $company = Company::first();
      
      if ($request->hasFile('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('public/uploads');
        $profilePicturePath = str_replace('public/', '', $profilePicturePath);
      } else {
        $profilePicturePath = $request->profile_picture;
      }
      $company->name = $request->name;
      $company->address = $request->address;
      $company->commercial_number = $request->commercialNumber;
      $company->tax_number = $request->taxNumber;
      $company->logo = $profilePicturePath;
      $company->save();
      return $company;
    } catch (\Throwable $ex) {
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}

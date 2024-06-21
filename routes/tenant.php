<?php

declare(strict_types=1);
use App\Models\User;
use App\Http\Controllers\CustomerController;

use App\Endpoints\Pieces\GetPiecesAction;
use App\Endpoints\Pieces\CreatePieceAction;
use App\Endpoints\Pieces\UpdatePieceAction;
use App\Endpoints\Pieces\DeletePieceAction;

use App\Endpoints\PieceList;

use App\Endpoints\Services\CreateServiceAction;
use App\Endpoints\Services\UpdateServicesAction;
use App\Endpoints\Services\DeleteServiceAction;
use App\Endpoints\Services\GetServicesAction;

use App\Endpoints\Prices\CreatePriceAction;
use App\Endpoints\Prices\UpdatePriceAction;
use App\Endpoints\Prices\DeletePriceAction;
use App\Endpoints\Prices\GetPricesAction;

use App\Endpoints\Users\GetUsersAction;
use App\Endpoints\Users\CreateUserAction;
use App\Endpoints\Users\DeleteUserAction;
use App\Endpoints\Users\UpdateUsersAction;

use App\Endpoints\CreateInvoice;
use App\Endpoints\CreateInvoiceWeb;
use App\Endpoints\GetStatstices;
use App\Endpoints\GetInvoiceFile;
use App\Endpoints\GetUserInvoice;
use App\Endpoints\UpdateInvoice;
use App\Endpoints\DeleteInvoiceAction;
use App\Endpoints\CustomerAction;
use App\Endpoints\GetUserAction;
use App\Endpoints\UpdateUserAction;
use App\Endpoints\EmailVerification;

use App\Endpoints\Signin;
use App\Endpoints\PhoneNumberVerifyAction;
// use App\Endpoints\WhatsApp;
use App\Endpoints\SearchCustomersAction;
// use App\Http\Controllers\PhoneNumberVerifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\Service_piece;
use App\Models\Service_piece_invoices;
use App\Transformers\InvoiceTransformer;
// use Throwable;
// use PDF;
use LaravelDaily\Invoices\Invoice as LdInvoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/
Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], static function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->middleware([
            'web',
            InitializeTenancyByDomain::class // Use tenancy initialization middleware of your choice
        ])->name('sanctum.csrf-cookie');
});

// Api middleware

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {


    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // email verification --- start ----
    // Route::get('/email/verify1/{id}/{hash}', function (EmailVerificationRequest $request) {
    //     dd($request);
    //     $request->fulfill();

     
    //     return redirect('/home');
    // })->middleware(['auth', 'signed'])->name('verification.verify');
    Route::get('/email/verify1/{id}/{hash}', EmailVerification::class)->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    // email verification --- end ----
    //
    Route::post('/signin', Signin::class);
    // Route::get('/send-whatsapp', WhatsApp::class);
    // Route::get('phone-verify', 'PhoneNumberVerifyController@show')->name('phoneverification.show');
    // Route::post('phone-verify', 'PhoneNumberVerifyController@verify')->name('phoneverification.verify');
    Route::post('/phone-verify', PhoneNumberVerifyAction::class);
    Route::middleware([
        'auth:sanctum'
        ])->group(function () {
            Route::post('/pieces', CreatePieceAction::class);
            Route::put('/pieces/{id}', UpdatePieceAction::class);
            Route::delete('/pieces/{id}', DeletePieceAction::class);
            Route::get('/pieces', GetPiecesAction::class);

            Route::post('/services', CreateServiceAction::class);
            Route::put('/services/{id}', UpdateServicesAction::class);
            Route::delete('/services/{id}', DeleteServiceAction::class);
            Route::get('/services', GetServicesAction::class);
            /** Prices */
            Route::post('/prices', CreatePriceAction::class);
            Route::put('/prices/{id}', UpdatePriceAction::class);
            Route::delete('/prices/{id}', DeletePriceAction::class);
            Route::get('/prices', GetPricesAction::class);

            Route::post('/users', CreateUserAction::class);
            Route::delete('/users/{id}', DeleteUserAction::class);
            Route::put('/users/{id}', UpdateUsersAction::class);
            Route::get('/users', GetUsersAction::class);

            Route::post('app-create-invoice', CreateInvoice::class);
            Route::post('/create-invoice', CreateInvoiceWeb::class);    
            Route::post('/create-customer', CustomerAction::class);
            Route::post('search-customer', SearchCustomersAction::class);
            Route::post('get-invoices-file', GetInvoiceFile::class);
            Route::post('/get-user-invoices', GetUserInvoice::class);
            Route::post('/update-invoice', UpdateInvoice::class);
            Route::delete('/invoice/{id}', DeleteInvoiceAction::class);
            Route::post('get-statstices', GetStatstices::class);
            Route::post('me', GetUserAction::class);
            Route::post('update-password', UpdateUserAction::class);
        });

    /** Service */
    
    Route::post('/token', function (Request $request) {
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required',
                ]);
            
                $user = User::where('email', $request->email)->first();
            
                if (! $user || ! Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'email' => ['The provided credentials are incorrect.'],
                    ]);
                }
                $request->validate(['device_name' => 'required']);
                $token = $user->createToken($request->device_name)->plainTextToken;
                return response()->json(['user' => $user, 'token' => $token]);
    });
});

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () { 
    Route::any('/invoice/{id}', function ($id) {
        $this->invocieid=$id;
        $service_piece_invoice_data=Service_piece_invoices::where('invoice_id',$this->invocieid)->get();
        $service_piece_data=Service_piece::where('id',$service_piece_invoice_data[0]->service_piece_id)->get();
        $service_data=Service::where('id',$service_piece_data[0]->service_id)->get();
        $piece_data=Piece::where('id',$service_piece_data[0]->piece_id)->get();
        $invoice_data=Invoice::where('id',$this->invocieid)->first();
        $service_piece_info_data=array("service_piece"=>$service_piece_data,'pieceinfo'=>$piece_data,'serviceinfo'=>$service_data,'customerinfo'=>$invoice_data);
        $piece_data[0]->invoiceservicelist=$service_piece_invoice_data;
        $piece_data[0]->invoiceData = $invoice_data;
        return view('invoice', ['invoice' => $piece_data]);
    });

    Route::get('/email/verify1/{id}/{hash}', EmailVerification::class)->name('verification.verify');
    Route::get('/{vue_capture?}', function() {
        return view('app');
    })->where('vue_capture', '[\/\w\.-]*');
});

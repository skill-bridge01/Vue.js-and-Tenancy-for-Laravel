<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Endpoints\EmailVerification;
// use App\Http\Controllers\WhatsAppController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/', function () {
//     return view('app');
// });

Route::post('/', function () {
    return view('login');
});

Route::get('/email/verify1/{id}/{hash}', EmailVerification::class)->name('verification.verify');

Route::get('/{vue_capture?}', function() {
    return view('app');
})->where('vue_capture', '[\/\w\.-]*');

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('verifiedphone');

Route::get('/home', 'HomeController@index')->name('home')->middleware('verifiedphone');

// Route::post('/login', function () {
//     return view('login');
// });
// Route::get('/send-whatsapp', [WhatsAppController::class, 'sendWhatsAppMessage']);
// Route::get('/app/{any}', function () {
//     $path = public_path('app/index.html');
//     abort_unless(file_exists($path), 400, 'Page is not Found!');
//     return file_get_contents($path);
// })
//     ->name('FrontEndApp');

// Route::middleware('splade')->group(function () {
//     // Registers routes to support the interactive components...
//     Route::spladeWithVueBridge();

//     // Registers routes to support password confirmation in Form and Link components...
//     Route::spladePasswordConfirmation();

//     // Registers routes to support Table Bulk Actions and Exports...
//     Route::spladeTable();

//     // Registers routes to support async File Uploads with Filepond...
//     Route::spladeUploads();

//     Route::get('/', function () {
//         return view('welcome');
//     });

//     Route::middleware('auth')->group(function () {
//         Route::get('/dashboard', function () {
//             return view('dashboard');
//         })->middleware(['verified'])->name('dashboard');

//         Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//         Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//         Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//     });

//     require __DIR__.'/auth.php';
// });

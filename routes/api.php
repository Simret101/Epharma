<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PatientController;
use Chapa\Chapa\Chapa;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PharmacistController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessageController;

Route::get('/messages', [MessageController::class, 'getAllMessages']);
Route::post('/messages/send', [MessageController::class, 'sendMessage']);
Route::get('/messages/{userId}', [MessageController::class, 'getMessages']);
Route::delete('/messages/{id}', [MessageController::class, 'deleteMessage']);
Route::post('/upload', [ImageController::class, 'store']);
Route::get('/images', [ImageController::class, 'index']); 
Route::get('/images/{id}', [ImageController::class, 'show']);
Route::post('/images/{id}', [ImageController::class, 'update']);
Route::delete('/images/{id}', [ImageController::class, 'destroy']);
Route::apiResource('pharmacists', PharmacistController::class);
Route::apiResource('orders', OrderController::class);
// Payment Routes
Route::get('success', [PaymentController::class, 'success']);
Route::get('error', [PaymentController::class, 'error']);
Route::post('pay', [PaymentController::class, 'pay']);
// Google Authentication
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::apiResource('patients', PatientController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require __DIR__ . '/auth.php';





Route::post('/initialize-payment', function () {
    $chapa = new Chapa();  // Create an instance of the Chapa class
    
    // Example data to initialize the payment
    $data = [
        'amount' => 1000,  // Replace with actual amount
        'email' => 'user@example.com', // Replace with user's email
        'tx_ref' => Chapa::generateReference('payment_prefix'),  // Generate a reference
    ];

    $response = $chapa->initializePayment($data);  // Call the initializePayment method

    // Return the response back to the user
    return response()->json($response);
});
Route::get('/verify-payment/{id}', function ($id) {
    $chapa = new Chapa();  // Create an instance of the Chapa class
    
    $response = $chapa->verifyTransaction($id);  // Call the verifyTransaction method

    return response()->json($response);
});


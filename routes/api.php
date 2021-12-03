<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
*/

Route::post('/shorten-now', [ShortenerController::class, 'shorten']);

Route::post('/redirected', [ShortenerController::class, 'registervisit']);

Route::get('/stats/{nametoken}', [ShortenerController::class, 'stats'])->where('nametoken', '[A-Za-z0-9]+');

Route::get('/{nametoken}', [ShortenerController::class, 'fetchfull'])->where('nametoken', '[A-Za-z0-9]+');


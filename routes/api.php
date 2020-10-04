<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Currency as Currency;
use \App\Models\Coin as Coin;
use \App\Models\Course as Course;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/v1/coin/{CoinName}/course', function (Request $request, $CoinName) {

    $CurrencyName = $request->currency;
    if(empty($CurrencyName))
        return json_encode(['error' => 'Currency is empty']);
        
    $Course = Course::join('Coins', 'Courses.CoinId', '=', 'Coins.Id')
                        ->join('Currency', 'Courses.CurrencyId', '=', 'Currency.Id')
                        ->where('Currency.ShortName', $CurrencyName)
                        ->where('Coins.ShortName', $CoinName)
                        ->select('Courses.Amount', 'Courses.LastUpdate')
                        ->get()->first();

    if(!$Course)
        return json_encode(['error' => 'Course not found']);

    return json_encode($Course);
});

Route::get('/v1/coin/{CoinName}', function (Request $request, $CoinName) {

    $Coin = Coin::where('ShortName', $CoinName)->first();
    if(!$Coin)
        return json_encode(['error' => "Coin `{$CoinName}` not found."]);

    return json_encode($Coin);
});
<?php

use Illuminate\Support\Facades\Route;
use \App\Models\Currency as Currency;
use \App\Models\Coin as Coin;
use \App\Models\Course as Course;

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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/Course/{CoinName}/{CurrencyName}', function ($CoinName, $CurrencyName) {

    $Coin = Coin::where('ShortName', $CoinName)->first();
    if(!$Coin)
        return "Coin `{$CoinName}` not found.";

    $Currency = Currency::where('ShortName', $CurrencyName)->first();
    if(!$Currency)
        return "Currency `{$CurrencyName}` not found.";
        
    $Course = Course::GetFor($Coin->Id, $Currency->Id);

    if(!$Course)
        return "Course `{$Coin->Name}->{$Currency->Name}` not found.";

    return view('Course-test', ['Course' => $Course, 'Coin' => $Coin, 'Currency' => $Currency]);
});
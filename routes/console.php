<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use \App\Models\Currency as Currency;
use \App\Models\Coin as Coin;
use \App\Models\Course as Course;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('update-courses', function () {
    $COINS_CREX24 = ['TRTT', 'BEAR'];
    $COINS_COINGECKO = ['ETH' => 'eth', 'TRON' => 'trx'];
    $CURRENCY_NAME = 'BTC';

    $Currency = Currency::where('ShortName', $CURRENCY_NAME)->get()->first();

    foreach($COINS_CREX24 as $v){
        $Res = Http::get("https://api.crex24.com/v2/public/tickers", ['instrument' => $v.'-'.$CURRENCY_NAME])->json();
        if(isset($Res['errorDescription'])){
            Log::warning('[Update Courses] Error: '.$Res['errorDescription']);
            $this->comment('Error: '.$Res['errorDescription']);
            continue;
        }
        $Res = $Res[0];

        $Coin = Coin::where('ShortName', $v)->get()->first();
        if(!$Coin)
            continue;
        $Course = Course::updateOrCreate(['CoinId' => $Coin->Id, 'CurrencyId' => $Currency->Id], ['Amount' => $Res['bid']]);

        //$Affected = Course::join('Coins', 'Courses.CoinId', '=', 'Coins.Id')
        //                ->join('Currency', 'Courses.CurrencyId', '=', 'Currency.Id')
        //                ->where('Currency.ShortName', $CURRENCY_NAME)
        //                ->where('Coins.ShortName', $v)
        //                ->update(['Courses.Amount' => $Res['bid']]);

        if(!$Course){
            Log::warning('[Update Courses] Error: Can`t update course in database.');
            $this->comment('Error: Can`t update course in database.');
        }
    }

    $Res = Http::get("https://api.coingecko.com/api/v3/coins/markets", ['vs_currency' => $CURRENCY_NAME])->json();
    if(isset($Res['error'])){
        Log::warning('[Update Courses] Error: '.$Res['errorDescription']);
        $this->comment('Error: '.$Res['errorDescription']);
    }
    else{
        $Counter = count($COINS_COINGECKO);
        foreach($Res as $v){
            reset($COINS_COINGECKO);
            foreach($COINS_COINGECKO as $n1 => $n2){
                if($v['symbol'] != $n2)
                    continue;
                $Counter--;

                $Coin = Coin::where('ShortName', $n1)->get()->first();
                if(!$Coin)
                    continue;
                $Course = Course::updateOrCreate(['CoinId' => $Coin->Id, 'CurrencyId' => $Currency->Id], ['Amount' => $v['current_price']]);
    
                //$Affected = Course::join('Coins', 'Courses.CoinId', '=', 'Coins.Id')
                //                ->join('Currency', 'Courses.CurrencyId', '=', 'Currency.Id')
                //                ->where('Currency.ShortName', $CURRENCY_NAME)
                //                ->where('Coins.ShortName', $c)
                //                ->update(['Courses.Amount' => $v['current_price']]);
    
                if(!$Course){
                    Log::warning('[Update Courses] Error: Can`t update course in database.');
                    $this->comment('Error: Can`t update course in database.');
                }
            }
            if($Counter <= 0)
                break;
        }
    } 

    $this->comment("Courses updated.");
})->purpose('Update courses');

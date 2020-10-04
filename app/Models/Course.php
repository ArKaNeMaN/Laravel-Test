<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'Courses';
    protected $primaryKey = 'Id';
    
    const UPDATED_AT = 'LastUpdate';
    const CREATED_AT = 'LastUpdate';

    protected $fillable = ['CoinId', 'CurrencyId', 'Amount'];

    public static function GetFor($CoinId, $CurrencyId){
        return self::where('CoinId', $CoinId)->where('CurrencyId', $CurrencyId)->first();
    }

    public function Coin(){
        return $this->belongsTo('\App\Models\Coin', 'CoinId', 'Id');
    }

    public function Currency(){
        return $this->belongsTo('\App\Models\Currency', 'CurrencyId', 'Id');
    }
}

<?php

namespace App\Test;
use Illuminate\Support\Facades\Http;

class Xorn
{
    protected $Addr;
    protected $Port;
    protected $User;
    protected $Pass;

    public function __construct($Addr, $Port, $User, $Pass){
        $this->Addr = $Addr;
        $this->Port = $Port;
        $this->User = $User;
        $this->Pass = $Pass;
    }

    public function getblockchaininfo(){
        return $this->Send(self::GetBody(__FUNCTION__, func_get_args()));
    }

    public function listaccounts(int $minconf = 1, bool $include_watchonly = false){
        return $this->Send(self::GetBody(__FUNCTION__, func_get_args()));
    }

    public function listtransactions(int $count = 10, int $skip = 0, bool $include_watchonly = false){
        $Params = func_get_args();
        $Params['account'] = '*';
        return $this->Send(self::GetBody(__FUNCTION__, $Params));
    }

    protected function Send($Req){
        return Http::withHeaders(['content-type: text/plain'])
                    ->withBody($Req, 'application/json')
                    ->withBasicAuth($this->User, $this->Pass)
                    ->timeout(15)
                    ->get($this->GetUrl())->json();
    }

    protected static function GetBody($Method, $Params = []){
        return json_encode([
            'jsonrpc' => '1.0',
            'id' => '0',
            'method' => (string) $Method,
            'params' => (array) $Params,
        ]);
    }

    protected function GetUrl(){
        return "http://{$this->Addr}:{$this->Port}/";
    }
}
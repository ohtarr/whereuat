<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\TeamsTrustedIp;

class PublicIp extends Model
{
    protected $guarded = [];

    public $cache;

    public static function all($columns = [])
    {

        $url = env('PUBLIC_IPS_URL');
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        foreach($array as $item)
        {
            $object = self::make($item);
            $ips[] = $object;
        }
        return collect($ips);
    }

    public function cacheAll($force = false)
    {
        if($force || !$this->cache)
        {
            $this->cache = $this->all();
        }
        return $this->cache;
    }

}

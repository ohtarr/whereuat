<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;


class PublicIp extends Model
{
    
    public static function all($columns = [])
    {

        $url = env('PUBLIC_IPS_URL');
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        return $array;
    }

}

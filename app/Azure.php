<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Azure extends Model
{
    private $tenant_id;
    private $client_id;
    private $client_secret;
    private $scope;
    
    public function __construct($tenant_id, $client_id, $client_secret, $scope)
    {
        $this->tenant_id = $tenant_id;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->scope = $scope;
    }

    public function getToken()
    {
        $token = Cache::get('msoauth_token');
        if($token)
        {
            return $token;
        }
  
        $verb = "post";
        $url = "https://login.microsoftonline.com/" . $this->tenant_id . "/oauth2/v2.0/token";
        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'scope' => $this->scope,
        ];
        $options = [];
        $params = [
            'headers'   =>  [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Accept'        => 'application/json',
             ],
            'form_params' => $body,
        ];

        $client = new Client($options);
        //Build a Guzzle POST request
        $apiRequest = $client->request($verb, $url, $params);
        $response = $apiRequest->getBody()->getContents();
        $array = json_decode($response,true);
        $token = $array['access_token'];
        $expires = $array['expires_in'];
        Cache::put('msoauth_token', $token, $expires*.95);
        return $token;
    }

    public static function getToken2()
    {
        $token = Cache::get('msoauth_token');
        if($token)
        {
            return $token;
        }
  
        $verb = "post";
        $url = "https://login.microsoftonline.com/" . env('AZURE_AD_TENANT_ID') . "/oauth2/v2.0/token";
        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => env('AZURE_AD_CLIENT_ID'),
            'client_secret' => env('AZURE_AD_CLIENT_SECRET'),
            'scope' => 'api://' . env('GIZMO_CLIENT_ID') . '/.default',
        ];
        $options = [];
        $params = [
            'headers'   =>  [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Accept'        => 'application/json',
             ],
            'form_params' => $body,
        ];

        $client = new Client($options);
        //Build a Guzzle POST request
        $apiRequest = $client->request($verb, $url, $params);
        $response = $apiRequest->getBody()->getContents();
        $array = json_decode($response,true);
        $token = $array['access_token'];
        $expires = $array['expires_in'];
        Cache::put('msoauth_token', $token, $expires*.95);
        return $token;
    }

}

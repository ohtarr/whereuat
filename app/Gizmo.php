<?php

/*
small library for accessing "Gizmo" API in a Laravel-esque fashion.
/**/

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;

class Gizmo extends Model
{
    //primary_Key of model.
    public static $key = "";
    //base URL to access Gizmo API.
    public static $base_url = "";
    //url suffix to access ALL endpoint
    public static $all_url_suffix = "";
    //url suffix to access GET endpoint
    public static $get_url_suffix = "";
    //url suffix to access FIND endpoint
    public static $find_url_suffix = "";
    //url suffix to access the SAVE endpoint
    public static $save_url_suffix = "";

    //search parameters used when performing a GET
    public $where = [];

    protected $guarded = [];

    //fields that are queryable by this model.
    public $queryable = [ 
    ];

    //fields that are EDITABLE by this model.
    public $saveable = [
    ];

    //Initialize the model with the BASE_URL from env.
    public static function init()
    {
        static::$base_url = env('GIZMO_URL');
    }

    //get ALL records of this model
    public static function all($columns = [])
    {
        $url = static::$base_url . static::$all_url_suffix;
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        $newarray = [];
        foreach($array as $item)
        {
            $newarray[] = static::make($item);
        }
        //print_r($newarray);
        return collect($newarray);
    }

    //Get a single record of this model.
    public static function find($id)
    {
        $body = [
            static::$key    =>  $id,
        ];
        $verb = "POST";
        $url = static::$base_url . static::$find_url_suffix;
        $params = [
            'headers'   =>  [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'body'  =>  json_encode($body),
        ];

        $client = new GuzzleHttpClient();
        //Build a Guzzle POST request
        $apiRequest = $client->request($verb, $url, $params);
        $response = $apiRequest->getBody()->getContents();
        $array = json_decode($response,true);
        //print_r($array);
        return static::make($array[0]);
    }

    //Add search querys to this model prior to performing a GET or FIRST.
    public static function where($key,$value)
    {
        $object = new static;
        foreach($object->queryable as $queryparam)
        {
            if(strtolower($key) == strtolower($queryparam))
            {
                $object->where[$queryparam] = $value;
                break;
            }
        }
        return $object;
    }

    //Execute query and return results in a COLLECTION.
    public function get()
    {
        foreach($this->where as $parameter => $value)
        {
            $query[$parameter] = $value; 
        }
        $body = $query;
        $verb = "POST";
        $url = static::$base_url . static::$get_url_suffix;
        $params = [
            'headers'   =>  [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'body'  =>  json_encode($body),
        ];

        $client = new GuzzleHttpClient();
        //Build a Guzzle POST request
        $apiRequest = $client->request($verb, $url, $params);
        $response = $apiRequest->getBody()->getContents();
        $array = json_decode($response,true);
        $newarray = [];
        foreach($array as $item)
        {
            $newarray[] = static::make($item);
        }
        //print_r($newarray);
        return collect($newarray);
    }

    //Perform a GET including all current WHERE queries, and return a single instance of this model.
    public function first()
    {
        return $this->get()->first();
    }

    //Get a fresh copy of this model from the database.  Return single instance of this model.
    public function fresh($with = [])
    {
        if(!$this->{static::$key})
        {
            print "No KEY value found.\n";
            return null;
        }
        return $this->find($this->{static::$key});
    }

    //Save model.
    public function save($options = [])
    {
        print "Key : " . $this->{static::$key} . "\n";
        if($this->{static::$key})
        {
            print "Civic Addresses are not editable.\n";
            return null;
        }
        foreach($this->saveable as $param)
        {
            foreach($this->toArray() as $attribute => $value)
            {
                if(strtolower($param) == strtolower($attribute))
                {
                    $body[$param] = $value;
                    break;
                }
            }
        }

        $verb = "POST";
        $url = static::$base_url . static::$save_url_suffix;
        $params = [
            'auth'  =>  [
            ],
            'headers'   =>  [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'body'  =>  json_encode($body),
        ];

        $client = new GuzzleHttpClient();
        //Build a Guzzle POST request
        $apiRequest = $client->request($verb, $url, $params);
        $response = $apiRequest->getBody()->getContents();
        $array = json_decode($response,true);
        print_r($array);
//        $object = self::make($array[0]);
  //      $object->original = $object->attributes;
    //    return $object;       
        /**/
    }

    //Delete model.
    public function delete()
    {


    }
}
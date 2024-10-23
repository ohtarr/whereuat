<?php

/*
small library for accessing "Gizmo" API in a Laravel-esque fashion.
/**/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\E911;
use \EmergencyGateway\EGW;
use App\Models\DeviceSwitch;

class E911Switch extends Model
{
    protected $connection = 'e911_mysql';
    protected $table = 'layer2_switches';
    //primary_Key of model.
    public $primaryKey = "switch_id";

    protected $guarded = [];

/*     //Initialize the model with the BASE_URL from env.
    public static function init()
    {
        parent::init();
        static::$all_url = env('E911_SWITCH_URL');
        static::$soap_url = env('E911_SWITCH_SOAP_URL');
        static::$soap_wsdl = env('E911_SWITCH_SOAP_WSDL');
    } */

    public static function createEgw()
    {
        return new EGW(
                env('E911_SWITCH_SOAP_URL'),
                env('E911_SWITCH_SOAP_WSDL'),
                env('E911_SOAP_USER'),
                env('E911_SOAP_PASS'),
                env('E911_SNMP_RW')
        );
    }

    public function getEgw()
    {
        if(!$this->egw)
        {
            $this->egw = $this->createEgw();
        }
        return $this->egw;
    }

    public static function getById($id)
    {
        return static::find($id);
    }

    public static function getByIp($ip)
    {
        return static::where('switch_ip',$ip)->first();
    }

    public static function add($ip, $vendor, $erl, $name)
    {

        $EGW = static::createEgw();

        $params = [
            'switch_ip'             =>  $ip,
            'switch_vendor'         =>  $vendor,
            'switch_erl'            =>  $erl,
            'switch_description'    =>  $name,
        ];

        try{
            $RESULT = $EGW->add_switch($params);
        } catch (\Exception $e) {
            print $e->getMessage();
        }
        return $RESULT;
    }

    public static function modify($ip, $vendor, $erl, $name)
    {

        $EGW = static::createEgw();

        $params = [
            'switch_ip'             =>  $ip,
            'switch_vendor'         =>  $vendor,
            'switch_erl'            =>  $erl,
            'switch_description'    =>  $name,
        ];

        try{
            $RESULT = $EGW->update_switch($params);
        } catch (\Exception $e) {
            print $e->getMessage();
        }
        return $RESULT;
    }

    public static function remove($ip)
    {

        $EGW = static::createEgw();

        try{
            $RESULT = $EGW->delete_switch($ip);
        } catch (\Exception $e) {
            print $e->getMessage();
        }
        return $RESULT;
    }

    //attempt to delete E911Switch.
    //returns TRUE or FALSE.
    public function delete()
    {
        if($this->switch_ip)
        {
            try{
                $this->getEgw()->delete_switch($this->switch_ip);
            } catch (\Exception $e) {
                print $e->getMessage();
            }
            if(!$this->getByIp($this->switch_ip))
            {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getDeviceSwitch()
    {
        $switches = DeviceSwitch::all();
        return $switches->where('ip', $this->switch_ip)->first();
    }

}
//E911Switch::init();
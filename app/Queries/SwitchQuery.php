<?php

namespace App\Queries;

use Illuminate\Http\Request;
use App\DeviceSwitch;

class SwitchQuery
{
    public static function apply(Request $request)
    {
        $allowed = [
            'name'                      =>  'whereSwitch',
            'model'                     =>  'whereSwitch',
            'serial'                    =>  'whereSwitch',
            'ip'                        =>  'whereSwitch',
            'mac'                       =>  'whereSwitch',
            'status'                    =>  'whereSwitch',
            'snmp_site'                 =>  'whereSnmp',
            'snmp_erl'                  =>  'whereSnmp',
            'snmp_mon'                  =>  'whereSnmp',
            'snmp_alert'                =>  'whereSnmp',
        ];

        $return = DeviceSwitch::all();

        foreach($request->all() as $key => $value)
        {
            foreach($allowed as $akey => $method)
            {
                if(strtolower($akey) == strtolower($key))
                {
                    $return = $return->$method($akey,$value);
                }
            }
        }

        return $return;
    }
}
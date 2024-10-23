<?php

namespace App\Queries;

use Illuminate\Http\Request;
use App\Models\Dhcp;

class DhcpQuery
{
    public static function apply(Request $request)
    {
        $allowed = [
            'scopeID'                   =>  'whereDhcp',
            'subnetMask'                =>  'whereDhcp',
            'name'                      =>  'whereDhcp',
            'state'                     =>  'whereDhcp',
            'description'               =>  'whereDhcp',
            'reservations_ipAddress'    =>  'whereReservations',
            'reservations_clientId'     =>  'whereReservations',
            'reservations_name'         =>  'whereReservations',
            'reservations_description'  =>  'whereReservations',
        ];

        $return = Dhcp::all();

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
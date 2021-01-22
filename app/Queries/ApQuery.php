<?php

namespace App\Queries;

use Illuminate\Http\Request;
use App\Ap;

class ApQuery
{
    public static function apply(Request $request)
    {
        $allowed = [
            'name'                      =>  'whereAp',
            'model'                     =>  'whereAp',
            'serial'                    =>  'whereAp',
            'mac'                       =>  'whereAp',
            'ip'                        =>  'whereAp',
            'wlc'                       =>  'whereAp',
            'group'                     =>  'whereAp',
            'flags'                     =>  'whereAp',
            'status'                    =>  'whereAp',
            'neighbor_name'             =>  'whereNeighbor',
            'neighbor_ip'               =>  'whereNeighbor',
            'neighbor_local_interface'  =>  'whereNeighbor',
            'neighbor_remote_interface' =>  'whereNeighbor',
            'bssids_bssid'              =>  'whereBssid',
            'bssids_essid'              =>  'whereBssid',
            'bssids_phy'                =>  'whereBssid',
            'bssids_channel'            =>  'whereBssid',
            'bssids_eirp'               =>  'whereBssid',
        ];

        $return = Ap::all();

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
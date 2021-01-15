<?php

namespace App\Search;

use App\Dhcp;
use Illuminate\Http\Request;

class DhcpSearch
{
    public static function apply(Request $request)
    {
        $allowed = [
            //'ip'                      =>  'whereDhcp',
            'scopeID'                 =>  'whereDhcp',
            'subnetMask'              =>  'whereDhcp',            
            'name'                    =>  'whereDhcp',
            'state'                   =>  'whereDhcp',
            'description'             =>  'whereDhcp',
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
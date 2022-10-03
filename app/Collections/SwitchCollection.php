<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class SwitchCollection extends Collection
{

    public function find($key, $default = NULL)
    {
        $return = $this->where('ip',$key)->first();
        return $return;
    }

    public function whereSwitch($key, $value = NULL)
    {
        $return = $this->filter(function ($item) use ($key, $value) {
            return false !== stripos(strtolower($item->$key), strtolower($value));
        });
        return $return;
    }

    public function whereSnmp($key,$value)
    {
        if(substr($key,0,5) == 'snmp_')
        {
            $key = substr($key,5);
        }
        $return = $this->filter(function ($item) use ($key, $value) {
            if(!isset($item->snmp_loc['json'][$key]))
            {
                return false;
            }
            return false !== stripos(strtolower($item->snmp_loc['json'][$key]), strtolower($value));
        });
        return $return;
    }

    public function withLocations()
    {
        return $this->map(function ($item, $key) {
            return $item->withLocation();
        });
    }

}
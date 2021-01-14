<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ApCollection extends Collection 
{

    public function whereAp($key, $value = NULL)
    {
        //$aps = self::all();

        $return = $this->filter(function ($item) use ($key, $value) {
            return false !== stripos(strtolower($item->$key), strtolower($value));
        });
        return $return;
    }

    public function whereBssid($key,$value)
    {
        if(substr($key,0,7) == 'bssids_')
        {
            $key = substr($key,7);
        }
        $return = $this->filter(function ($item) use ($key,$value) {
            if(isset($item->bssids))
            {
                foreach($item->bssids as $bssid)
                {
                    if($bssid[$key] == $value)
                    {
                        return true;
                    }
                }
            }
        });
        return $return;
    }

    public function hasSsids()
    {
        $return = $this->filter(function ($item) {
            if(isset($item->bssids))
            {
                if(!empty($item->bssids))
                {
                    return true;
                }
            }
        });
        return $return;
    }

    public function whereNeighbor($key,$value)
    {
        if(substr($key,0,9) == 'neighbor_')
        {
            $key = substr($key,9);
        }
        $return = $this->filter(function ($item) use ($key, $value) {
            return false !== stripos(strtolower($item->neighbor[$key]), strtolower($value));
        });
        return $return;
    }

    public function getAllSsids()
    {
        $allssids = [];
        foreach($this as $ap)
        {
            if(isset($ap->bssids))
            {
                foreach($ap->bssids as $bssid)
                {
                    if(isset($allssids[$bssid['essid']]))
                    {
                        $allssids[$bssid['essid']]++;
                    } else {
                        $allssids[$bssid['essid']] = 1;
                    }
                }
            }
        }
        return $allssids;
    }

}
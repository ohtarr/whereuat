<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Site;

class DhcpCollection extends Collection 
{

    public function whereDhcp($key, $value = NULL)
    {
        return $this->filter(function ($item) use ($key, $value) {
            return false !== stripos(strtolower($item->$key), strtolower($value));
        })->values();
    }

    public function whereOptions($key,$value)
    {
        if(substr($key,0,8) == 'options_')
        {
            $key = substr($key,8);
        }
        $return = $this->filter(function ($item) use ($key,$value) {
            if(isset($item->dhcpOptions))
            {
                foreach($item->dhcpOptions as $option)
                {
                    if(is_array($option))
                    {
                        //foreach($option as $)
                    }
                    if($option[$key] == $value)
                    {
                        return true;
                    }
                }
            }
        });
        return $return->values();
    }

    public function whereReservations($key,$value)
    {
        if(substr($key,0,13) == 'reservations_')
        {
            $key = substr($key,13);
        }
        $return = $this->filter(function ($item) use ($key,$value) {
            if(isset($item->reservations))
            {
                if(!empty($item->reservations))
                {
                    foreach($item->reservations as $reservation)
                    {
                        $check = stripos(strtolower($reservation[$key]), strtolower($value));
                        if($check !== false)
                        {
                            return true;
                        }
/*                         return false !== stripos(strtolower($reservation[$key]), strtolower($value)); */
                    }
                }
            }
        });
        return $return->values();
    }


    public function findScopesByName($value)
    {
        return $this->whereDhcp('name',$value);
    }

    public function findScopesByDescription($value)
    {
        return $this->whereDhcp('description',$value);
    }

    public function containsIp($value)
    {
        return $this->filter(function ($item) use ($value) {
            //return false !== stripos(strtolower($item->$key), strtolower($value));
            return $item->isInScope($value);
        })->first();
    }

    public function withoutReservations()
    {
        return $this->map(function ($item, $key) {
            return $item->withoutReservations();
        });
    }

    public function withoutFailover()
    {
        return $this->map(function ($item, $key) {
            return $item->withoutFailover();
        });
    }

    public function withoutOptions()
    {
        return $this->map(function ($item, $key) {
            return $item->withoutOptions();
        });
    }

    public function withSites()
    {
        $sites = Site::all();
        return $this->map(function ($item, $key) use ($sites) {
            foreach($sites as $site)
            {
                if(stripos($item->name,$site->name) !== false)
                {
                    $item->withSite();
                    //$item->location = $site->load('defaultBuilding.address');                    
                    return $item;
                }
            }
        });
    }

}
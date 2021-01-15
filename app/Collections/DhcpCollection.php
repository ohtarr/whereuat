<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class DhcpCollection extends Collection 
{

    public function whereDhcp($key, $value = NULL)
    {
        return $this->filter(function ($item) use ($key, $value) {
            return false !== stripos(strtolower($item->$key), strtolower($value));
        });
    }

    public function findScope($value)
    {
        return $this->filter(function ($item) use ($value) {
            //return false !== stripos(strtolower($item->$key), strtolower($value));
            return $item->isInScope($value);
        })->first();
    }

}
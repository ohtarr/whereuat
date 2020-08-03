<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceNowLocation;
use App\TeamsCivic;

class Address extends Model
{

    public function site()
    {
        return $this->hasOne('App\Site');
    }

    public function buildings()
    {
        return $this->hasMany('App\Building');
    }

    public function getTeamsCivic()
    {
        return TeamsCivic::find($this->teams_civic_id);
    }

}

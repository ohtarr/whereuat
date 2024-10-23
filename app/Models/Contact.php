<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Building;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

}

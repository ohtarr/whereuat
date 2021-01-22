<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceNowLocation;
use App\TeamsCivic;
use App\Collections\AddressCollection;

class Address extends Model
{

    protected $appends = ['street1','street2'];

    public function newCollection(array $models = []) 
    { 
       return new AddressCollection($models); 
    }

    //WHEREUAT_ADDRESS to SERVICENOWLOCATION field mappings
    public $teamsAddressMapping = [
        'street_number'             => 'houseNumber',
        'predirectional'            => 'preDirectional',
        'street_name'               => 'streetName',
        'street_suffix'             => 'streetSuffix',
        'postdirectional'           => 'postDirectional',
        'city'                      => 'city',
        'state'                     => 'state',
        'postal_code'               => 'postalCode',
        'country'                   => 'countryOrRegion',
        'latitude'                  => 'latitude',
        'longitude'                 => 'longitude',
    ];

    //WHEREUAT_ADDRESS to SERVICENOWLOCATION field mappings
    public $snowAddressMapping = [
        'street_number'             => 'u_street_number',
        'predirectional'            => 'u_street_predirectional',
        'street_name'               => 'u_street_name',
        'street_suffix'             => 'u_street_suffix',
        'postdirectional'           => 'u_street_postdirectional',
        'secondary_unit_indicator'  => 'u_secondary_unit_indicator',
        'secondary_number'          => 'u_secondary_number',
        'city'                      => 'city',
        'state'                     => 'state',
        'postal_code'               => 'zip',
        'country'                   => 'country',
        'latitude'                  => 'latitude',
        'longitude'                 => 'longitude',
    ];

    //RELATIONSHIP to BUILDING
    public function building()
    {
        return $this->hasOne('App\Building');
    }

    public function getStreet1Attribute()
    {
        $array = [
            'street_number',
            'predirectional',
            'street_name',
            'street_suffix',
            'postdirectional',
        ];
        $street1 = "";
        foreach($array as $element)
        {
            if($this->$element)
            {
                if($street1)
                {
                    $street1 .= " ";
                }
                $street1 .= $this->$element;
            }
        }
        return $street1;
    }

    public function getStreet2Attribute()
    {
        $array = [
            'secondary_unit_indicator',
            'secondary_number',
        ];
        $street2 = "";
        foreach($array as $element)
        {
            if($this->$element)
            {
                if($street2)
                {
                    $street2 .= " ";
                }
                $street2 .= $this->$element;
            }
        }
        return $street2;
    }

    //retrieve TEAMS CIVIC from TEAMs.
    public function getTeamsCivic()
    {
        if(!$this->teams_civic_id)
        {
            return null;
        }
        return TeamsCivic::find($this->teams_civic_id);
    }

    public function getSite()
    {
        if($this->site)
        {
            return $this->site;
        }
        if($this->buildings->first())
        {
            return $this->buildings->first()->site;
        }
    }

    public function syncAdd()
    {
        print "Syncing ADDRESS...\n";
        if(!$this->teams_civic_id)
        {
            print "TEAMSCIVIC does not exist...  Creating!\n";
            $civic = $this->createTeamsCivic();
            if(!$civic)
            {
                $error = "Failed to create TEAMS CIVIC!\n";
                print $error;
                throw new \Exception($error);
            }
            print "Created TEAMS CIVIC with ID {$civic->civicAddressId}...\n";
        } else {
            print "Found existing TEAMS CIVIC ID {$this->teams_civic_id}...\n";
            return $this->teams_civic_id;
        }
    }

    public function compareTeamsCivic($civic = null)
    {
        if(!$civic)
        {
            $civic = $this->getTeamsCivic();
        }

        $matches = true;
        foreach($this->teamsAddressMapping as $addressKey => $teamsKey)
        {
            if($addressKey == "country")
            {
                if($this->iso3166ToAlpha2($this->$addressKey) != $this->iso3166ToAlpha2($civic->$teamsKey))
                {
                    $matches = false;
                    break;
                }
            } else {
                if($this->$addressKey != $civic->$teamsKey)
                {
                    $matches = false;
                    break;
                }
            }
        }
        return $matches;
    }

    public function createTeamsCivic()
    {
        $civic = new TeamsCivic;
        $civic->companyName = $this->getSite()->name;
        $civic->description = $this->getSite()->name;
        foreach($this->teamsAddressMapping as $addressKey => $teamsKey)
        {
            $civic->$teamsKey = $this->$addressKey;
        }
        $civic->countryOrRegion = $this->iso3166ToAlpha2($this->country);
        $civicid = $civic->save();
        if(!$civicid)
        {
            throw new \Exception("Failed to create TEAMS CIVIC!");
        }
        $civic->civicAddressId = $civicid;
        //$civic = TeamsCivic::find($civicid);
        $status = $civic->validate();
        if($status == false)
        {
            throw new \Exception("Failed to validate TEAMS CIVIC!");
        }
        $this->teams_civic_id = $civic->civicAddressId;
        $this->save();
        return $civic;
    }

    public static function iso3166ToAlpha2($countrycode)
    {
        $codes = [
            'USA'   =>  'US',
            'US'    =>  'US',
            'CAN'   =>  'CA',
            'CA'    =>  'CA',
            'MEX'   =>  'MX',
            'MX'    =>  'MX',
        ];
        foreach($codes as $old => $new)
        {
            if(strtoupper($countrycode) == $old)
            {
                return $new;
            }
        }
    }

    public static function iso3166ToAlpha3($countrycode)
    {
        $codes = [
            'US'    =>  'USA',
            'USA'   =>  'USA',
            'CA'    =>  'CAN',
            'CAN'   =>  'CAN',
            'MX'    =>  'MEX',
            'MEX'   =>  'MEX',
        ];
        foreach($codes as $old => $new)
        {
            if(strtoupper($countrycode) == $new)
            {
                return $old;
            }
        }
    }

}

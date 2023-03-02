<?php

/*
small library for accessing "Gizmo" API in a Laravel-esque fashion.
/**/

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\E911;
use \EmergencyGateway\EGW;
use App\TMS;
use App\Room;
use App\E911Switch;

class E911Erl extends Model
{
    protected $connection = 'e911_mysql';
    protected $table = 'locations';
    //primary_Key of model.
    public $primaryKey = "location_id";

    protected $guarded = [];

    //Initialize the model with the BASE_URL from env.
 /*    public static function init()
    {
        parent::init();
        static::$all_url = env('E911_ERL_URL');
        static::$soap_url = env('E911_ERL_SOAP_URL');
        static::$soap_wsdl = env('E911_ERL_SOAP_WSDL');
    } */

    //public function __construct()
    //{
/*         $this->egw = new EGW(
            env('E911_ERL_SOAP_URL'),
            env('E911_ERL_SOAP_WSDL'),
            env('E911_SOAP_USER'),
            env('E911_SOAP_PASS'),
            env('E911_SNMP_RW')
        ); */

        //$this->soap_url = env('E911_ERL_SOAP_URL');
        //$this->soap_wsdl = env('E911_ERL_SOAP_WSDL');
        //$this->username = env('E911_SOAP_USER');
        //$this->password = env('E911_SOAP_PASS');
        //$this->snmp_community = env('E911_SNMP_RW');
    //}

    public static function createEgw()
    {
        return new EGW(
                env('E911_ERL_SOAP_URL'),
                env('E911_ERL_SOAP_WSDL'),
                env('E911_SOAP_USER'),
                env('E911_SOAP_PASS'),
                env('E911_SNMP_RW')
        );
    }

    public function getEgw()
    {
        if(!$this->egw)
        {
            $this->egw = $this->createEgw();
        }
        return $this->egw;
    }

    //Fetch E911Erl by Name (erl_id)
    //Returns an E911Erl object
    public static function getByName($name)
    {
        return static::where('erl_id',$name)->first();
    }

    //Fetch E911Erl by ID (location_id)
    //Returns an E911Erl object
    public static function getById($id)
    {
        return static::where('location_id',$id)->first();
    }

    //Attempt to add an E911Erl.
    //Requires NAME and ADDRESS(array).  ELIN DID is optional.
    //returns an E911Erl object or NULL.
    public static function add($name, array $address, $elin = null)
    {

        /* $address format
            [
                "LOC" => "ste 25",
                "HNO" => "123",
                "RD" => "test st",
                "A3" => "Omaha",
                "A1" => "NE",
                "country" => "us",
                "PC" => "68137",
            ]
        */
        $EGW = static::createEgw();

/*         if($address['country'] == "CAN")
        {
            $elin = $this->getTMSElin();
            if(!$elin)
            {
                $elin = $this->reserveElin();
            }
            if(!$elin)
            {
                throw \Exception('Unable to find an ELIN for Canadian site!');
            }
        } */
        $EGW->addERL($name,$address,$elin);
        return static::getByName($name);
    }

    //attempt to delete E911Erl.
    //returns TRUE or FALSE.
    public function delete()
    {
        if($this->erl_id)
        {
            try{
                //print_r($this);
                $egw = $this->getEgw();
                //print_r($egw);
                $egw->deleteERL($this->erl_id);
                //static::getEgw()->deleteERL($this->erl_id);
            } catch (\Exception $e) {
                print $e->getMessage() . "\n";
            }
            if(!$this->getByName($this->erl_id))
            {
                $this->releaseElin();
                return true;
            } else {
                return false;
            }
        }
    }

    //Delete E911Erl by name.
    //not used, probably delete.
    public static function deleteByName($name)
    {
        $erl = static::getByName($name);
        $E911Switches = static::getE911SwitchesByLocId($erl->location_id);

        if($E911Switches->isNotEmpty())
        {
            print "Switches are currently assigned to this ERL!  Remove Switches first!";
            return null;
        }
        $RESULT = null;
        $EGW = static::createEgw();

        try{
            $RESULT = $EGW->deleteERL($name);
        } catch (\Exception $e) {
            print $e->getMessage();
        }
        return $RESULT;
    }

    //Create a new TMS object to access the TMS system.
    //returns a TMS object.
    public function getTMS()
    {
        return new TMS(env('TMS_URL'),env('TMS_USERNAME'),env('TMS_PASSWORD'));        
    }

    //Attempt to fetch a TMS Elin for this E911Erl.
    //Returns an array
    public function getTMSElin()
    {
        $elins = $this->getTMS()->getCaElins();
        $elin = $elins->where('name',$this->erl_id)->first();
        if($elin)
        {
            return $elin;
        }
    }

    //Attempts to reserve an available elin for this E911Erl object.
    //returns an array or null.
    public function reserveElin()
    {
        return $this->getTMS()->reserveCaElin($this->erl_id);
    }

    //releases associated TMS Elin for this E911Erl
    //returns an array or null.
    public function releaseElin()
    {
        $elin = $this->getTMSElin();
        if(!$elin)
        {
            return null;
        }
        return $this->getTMS()->releaseCaElin($elin['id']);
    }

    //Fetch associated ROOM object
    //returns a Room object.
    public function getRoom()
    {
        return Room::where('data->E911Erl_id', $this->location_id)->first();
    }

    public static function getE911SwitchesByLocId($location_id)
    {
        return E911Switch::where('switch_default_erl_id',$location_id)->get();
    }

    //Fetch any associated E911Switches to this E911Erl
    //returns a collection
    public function getE911Switches()
    {
        return $this->getE911SwitchesByLocId($this->location_id);
    }

    public function deleteAllE911Switches()
    {
        $switches = $this->getE911Switches();
        foreach($switches as $switch)
        {
            $switch->delete();
        }
    }

    public function purge()
    {
        $this->deleteAllE911Switches();
        $this->releaseElin();
        $this->delete();
    }

}
//E911Erl::init();
<?php

//Example Model to place in your App folder.

namespace App;

use ohtarr\ServiceNowModel;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Address;
use App\Site;
use App\Contact;
use App\ServiceNowUser;
use App\Building;
use App\Room;

class ServiceNowLocation extends ServiceNowModel
{
	protected $guarded = [];

	public $table = "cmn_location";

    public function __construct(array $attributes = [])
    {
        $this->snowbaseurl = env('SNOWBASEURL'); //https://mycompany.service-now.com/api/now/v1/table
        $this->snowusername = env("SNOWUSERNAME");
        $this->snowpassword = env("SNOWPASSWORD");
		parent::__construct($attributes);
    }

    public function address()
    {
        return $this->hasOne('App\Address','loc_sys_id','sys_id');
    }

    public function getBusinessContact()
    {
        if($this->contact)
        {
            return ServiceNowUser::find($this->contact['value']);
        }
    }

    public function getItContact()
    {
        if($this->u_on_site_contact)
        {
            return ServiceNowUser::find($this->u_on_site_contact['value']);
        }
    }

    public function getItManagerContact()
    {
        if($this->u_field_support_manager)
        {
            return ServiceNowUser::find($this->u_field_support_manager['value']);
        }
    }

    public function get911Contact()
    {
        if($contact = $this->getBusinessContact())
        {
            return $contact;
        }

        if ($contact = $this->getItContact()){
            return $contact;
        }

        if ($contact = $this->getItManagerContact()){
            return $contact;
        }
    }

    public function sync()
    {
        //Check if site exists, if so blow up
        $address = Address::where('loc_sys_id',$this->sys_id)->first();

        if($address)
        {
            print "Existing ADDRESS with ID {$address->id} found.\n";
        } else {
            print "ADDRESS not found.  Creating new ADDRESS....";
            $address = $this->createNewAddress();
            print "ID " . $address->id . " Created.\n";
        }
        
        $site = Site::where('name',$this->name)->first();

        if($site)
        {
            print "Existing SITE with ID {$site->id} found.\n";
            $bldgcount = $site->buildings->count();
            if($bldgcount > 0)
            {
                print "Found {$bldgcount} BUILDINGS linked to SITE\n";
            }
            $contact = $site->contact;
            if($contact)
            {
                print "Found CONTACT with id {$contact->id} linked to SITE.\n";
            }
        } else {
            print "SITE not found, creating CONTACT, SITE, BUILDING, and ROOM.\n";
            $this->createAll();
        }
    }

    public function createNewAddress()
    {
        $address = new Address;
        $address->loc_sys_id = $this->sys_id;
        $address->save();
        return $address;
    }
    public function createNewContact()
    {
        $econtact = $this->get911Contact();
        $contact = new Contact;
        $contact->name = $this->name . "_911_CONTACT";
        $contact->description = "Default Contact created by system.";
        $contact->phone = preg_replace('/\D+/', '', $econtact->phone);
        $contact->email = $econtact->email;
        $contact->save();
        return $contact;
    }

    public function createNewSite()
    {
        $contact = $this->createNewContact();
        $site = new Site;
        $site->name = $this->name;
        //$site->description = "Default Site created by system.";
        $site->address_id = $this->address->id;
        $site->contact_id = $contact->id;
        $site->save();

        return $site;
    }

    public function createNewBuilding()
    {
        $building = new Building;
        $building->name = "DEFAULT_BUILDING";
        $building->description = "Default Building created by system";
        $building->site_id = $this->address->site->id;
        $building->save();

        return $building;
    }

    public function createNewRoom()
    {
        $room = new Room;
        $room->name = "MAIN_NETWORK_CLOSET";
        $room->description = "Default Room created by system";
        $room->building_id = $this->address->site->buildings->first()->id;
        $room->save();
        return $room;
    }

    public function createAll()
    {
        $site = $this->createNewSite();
        $building = $this->createNewBuilding();
        $room = $this->createNewRoom();
        if($site && $building && $room)
        {
            return true;
        }
        return false;
    }

}

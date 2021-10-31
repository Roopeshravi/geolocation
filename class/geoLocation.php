<?php

require_once(dirname(dirname(__FILE__)) . '/src/StaticCredentials.php');
require_once(dirname(dirname(__FILE__)) . '/src/ClientBuilder.php');
require_once(dirname(dirname(__FILE__)) . '/src/International_Street/Lookup.php');
require_once(dirname(dirname(__FILE__)) . '/src/International_Street/Client.php');
use SmartyStreets\PhpSdk\StaticCredentials;
use SmartyStreets\PhpSdk\ClientBuilder;
use SmartyStreets\PhpSdk\International_Street\Lookup;


class GeoLocation {
 private $street,
            $address1,
            $address2,
            $locality,
            $administrativeArea,
            $postalCode,
            $country,
            $geolocate,
            $countryISO3; 

    public function __construct($obj = null) {
        if ($obj == null)
            return;

        $this->geolocate = isset($obj['geolocate'])?$obj['geolocate']:'';
        $this->address1 = isset($obj['address1'])?$obj['address1']:'';
        $this->address2 = isset($obj['address2'])?$obj['address2']:'';
        $this->street = isset($obj['street'])?$obj['street']:'';
        $this->locality = isset($obj['locality'])?$obj['locality']:'';
        $this->administrativeArea = 'SP';
        $this->postalCode = isset($obj['postal_code'])?$obj['postal_code']:'';
        $this->countryISO3 = isset($obj['country_iso3'])?$obj['country_iso3']:'';
        $this->country = 'Brazil';
    }


    public function getAddressDetails() {
        $authId = 'aaa3d5f3-2308-c783-0742-7053a25c8018';
        $authToken = 'dOXJXh1RwFf7Umqzaj1Z';

        $staticCredentials = new StaticCredentials($authId, $authToken);

        $client = (new ClientBuilder($staticCredentials)) ->withLicenses(["international-global-plus-cloud"])
            ->buildInternationalStreetApiClient();

        $lookup = new Lookup();
        $lookup->setInputId("ID-8675309");
        $lookup->setGeocode(true); 
        $lookup->setOrganization("John Doe");
        $lookup->setAddress1($this->address1);
        $lookup->setAddress2($this->address2);
        $lookup->setLocality($this->locality);
        $lookup->setAdministrativeArea($this->administrativeArea);
        $lookup->setCountry($this->country);
        $lookup->setPostalCode($this->postalCode);

        $client->sendLookup($lookup); 
        $firstCandidate = $lookup->getResult()[0];
        $details=[];
        if($firstCandidate->getAnalysis()->getVerificationStatus() == 'Verified'){
        	$metadata = $firstCandidate->getMetadata();
        	$details['status'] = true;
        	$details['message'] = 'Success';
        	$details['latitude'] = $metadata->getLatitude();
        	$details['longitude'] = $metadata->getLongitude();
        }else{
        	$details['status'] = false;
        	$details['message'] = 'Invalid Address';
        	$details['latitude'] = '';
        	$details['longitude'] = '';
        }

        echo json_encode($details);
    }

    public function geoLocate(){
    	$location = $this->geolocate;
    	$search_url = "https://nominatim.openstreetmap.org/search?q=".$location."&format=json";

    	$httpOptions = [
    		"http" => [
    			"method" => "GET",
    			"header" => "User-Agent: Nominatim-Test"
    		]
    	];

    	$streamContext = stream_context_create($httpOptions);
    	$json = file_get_contents($search_url, false, $streamContext);
    	$decoded = json_decode($json, true);
    	$details=[];
        if(!empty($decoded)){
        	$details['status'] = true;
        	$details['message'] = 'Success';
        	$details['latitude'] = $decoded[0]["lat"];
        	$details['longitude'] = $decoded[0]["lon"];
        	$details['details'] = $decoded[0];
        }else{
        	$details['status'] = false;
        	$details['message'] = 'Invalid Address';
        	$details['latitude'] = '';
        	$details['longitude'] = '';
        }

        echo json_encode($details);
    }

}



// class InternationalExample {

//     public function run() {
//         $authId = 'Your SmartyStreets Auth ID here';
//         $authToken = 'Your SmartyStreets Auth Token here';

//         // We recommend storing your secret keys in environment variables instead---it's safer!
// //        $authId = getenv('SMARTY_AUTH_ID');
// //        $authToken = getenv('SMARTY_AUTH_TOKEN');

//         $staticCredentials = new StaticCredentials($authId, $authToken);

//         // The appropriate license values to be used for your subscriptions
//         // can be found on the Subscriptions page the account dashboard.
//         // https://www.smartystreets.com/docs/cloud/licensing
//         $client = (new ClientBuilder($staticCredentials)) ->withLicenses(["international-global-plus-cloud"])
//             ->buildInternationalStreetApiClient();

//         // Documentation for input fields can be found at:
//         // https://smartystreets.com/docs/cloud/international-street-api

//         $lookup = new Lookup();
//         $lookup->setInputId("ID-8675309");
//         $lookup->setGeocode(true); // Must be expressly set to get latitude and longitude.
//         $lookup->setOrganization("John Doe");
//         $lookup->setAddress1("Rua Padre Antonio D'Angelo 121");
//         $lookup->setAddress2("Casa Verde");
//         $lookup->setLocality("Sao Paulo");
//         $lookup->setAdministrativeArea("SP");
//         $lookup->setCountry("Brazil");
//         $lookup->setPostalCode("02516-050");

//         $client->sendLookup($lookup); // The candidates are also stored in the lookup's 'result' field.

//         $firstCandidate = $lookup->getResult()[0];

//         echo("Address is " . $firstCandidate->getAnalysis()->getVerificationStatus());
//         echo("\nAddress precision: " . $firstCandidate->getAnalysis()->getAddressPrecision() . "\n");

//         echo("\nFirst Line: " . $firstCandidate->getAddress1());
//         echo("\nSecond Line: " . $firstCandidate->getAddress2());
//         echo("\nThird Line: " . $firstCandidate->getAddress3());
//         echo("\nFourth Line: " . $firstCandidate->getAddress4());

//         $metadata = $firstCandidate->getMetadata();
//         echo("\nAddress Format: " . $metadata->getAddressFormat());
//         echo("\nLatitude: " . $metadata->getLatitude());
//         echo("\nLongitude: " . $metadata->getLongitude());
//     }
// }
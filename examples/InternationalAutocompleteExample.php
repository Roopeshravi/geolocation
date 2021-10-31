<?php

require_once(dirname(dirname(__FILE__)) . '/src/StaticCredentials.php');
require_once(dirname(dirname(__FILE__)) . '/src/ClientBuilder.php');
require_once(dirname(dirname(__FILE__)) . '/src/International_Autocomplete/Lookup.php');
require_once(dirname(dirname(__FILE__)) . '/src/International_Autocomplete/Client.php');
use SmartyStreets\PhpSdk\StaticCredentials;
use SmartyStreets\PhpSdk\ClientBuilder;
use SmartyStreets\PhpSdk\International_Autocomplete\Lookup;

$lookupExample = new InternationalAutocompleteExample();
$lookupExample->run();

class InternationalAutocompleteExample {

    public function run() {
       $authId = 'aaa3d5f3-2308-c783-0742-7053a25c8018';
       $authToken = 'dOXJXh1RwFf7Umqzaj1Z';

        // We recommend storing your secret keys in environment variables instead---it's safer!
        // $authId = getenv('aaa3d5f3-2308-c783-0742-7053a25c8018');
        // $authToken = getenv('dOXJXh1RwFf7Umqzaj1Z');

        $staticCredentials = new StaticCredentials($authId, $authToken);

        // The appropriate license values to be used for your subscriptions
        // can be found on the Subscriptions page the account dashboard.
        // https://www.smartystreets.com/docs/cloud/licensing
        $client = (new ClientBuilder($staticCredentials)) ->withLicenses(["international-autocomplete-cloud"])
            ->buildInternationalAutocompleteApiClient();

        // Documentation for input fields can be found at:
        // https://smartystreets.com/docs/cloud/international-street-api

        $lookup = new Lookup("Louis");
        $lookup->setCountry("FRA");
        $lookup->setLocality("Paris");

        $client->sendLookup($lookup); // The candidates are also stored in the lookup's 'result' field.
//echo'<pre>';print_r($lookup->getResult());exit;
        $firstCandidate = $lookup->getResult()[0];
        $metadata = $firstCandidate->getMetadata();
        echo("<br>\nAddress Format: " . $metadata->getAddressFormat());
        echo("<br>\nLatitude: " . $metadata->getLatitude());
        echo("<br>\nLongitude: " . $metadata->getLongitude());exit;
        foreach ($lookup->getResult() as $candidate) {
            echo($candidate->getStreet() . " " . $candidate->getLocality() . " " . $candidate->getCountryISO3() . "\n");
            
        };

    }
}
<?php 
// $lookupExample = new InternationalExample();
// $lookupExample->run();
 // require_once 'geoLocation.php';
 require_once(dirname(dirname(__FILE__)) . '/examples/geoLocation.php');
  $location = new geoLocation($_POST);
  return $location->getAddressDetails();


?>
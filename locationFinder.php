<?php 
 require_once(dirname(__FILE__) . '/class/geoLocation.php');
  $location = new geoLocation($_POST);
  return $location->getAddressDetails();


?>
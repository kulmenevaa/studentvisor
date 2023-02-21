<?php

namespace App\Helpers;

use Stevebauman\Location\Facades\Location;

class LocationHelper
{
    public function place($ip) 
    {
        if($location = Location::get($ip)) {
            $country = $location->countryName;
            $city = $location->cityName;
            return $country ? $country.', '.$city : $city;
        }
        return false;
    }
}
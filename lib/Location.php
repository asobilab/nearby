<?php

namespace Asobilab\Nearby;

class Location
{
    // GeodeticLatitude (測地緯度) - [一般的な「緯度」]
    protected $latitude;
    // GeodeticLongitude(測地経度)
    protected $longitude;

    public function __construct($lat, $lon)
    {
        $this->latitude = $lat;
        $this->longitude = $lon;
    }

    public function getCoordinate()
    {
        return ['latitude'  => $this->getLatitude(), 'longitude' => $this->getLongitude()];
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}

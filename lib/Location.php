<?php

class Location
{
	private $latitude;  // GeodeticLatitude (測地緯度) - [一般的な「緯度」]
	private $longitude; // GeodeticLongitude(測地経度)

	public Function __construct($lat,$lon)
	{
		self::setCoordinate($lat,$lon);
	}

	public Function setCoordinate($lat,$lon)
	{
		self::setLatitude($lat);
		self::setLongitude($lon);
	}

	public Function setLatitude($lat)
	{
		$this->latitude = $lat;
	}

	public Function setLongitude($lon)
	{
		$this->longitude = $lon;
	}

	public Function getCoordinate()
	{
		$lat = self::getLatitude();
		$lon = self::getLongitude();
		$coordinate = ['latitude' => "$lat" , 'longitude' => "$lon"];
		return $coordinate;
	}

	public Function getLatitude()
	{
		return $this->latitude;
	}

	public Function getLongitude()
	{
		return $this->longitude;
	}

}

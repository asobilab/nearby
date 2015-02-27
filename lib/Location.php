<?php

namespace Asobilab\Nearby;
use Exception;

class Location
{
    // GeodeticLatitude (測地緯度) - [一般的な「緯度」]
    protected $latitude;
    // GeodeticLongitude(測地経度)
    protected $longitude;

    public function __construct($lat, $lon)
    {
        try {
            // 入力値のValiadtion(共通)
            if ($this->validateParams($lat) === false) {
                throw new Exception('緯度に数値以外の指定がされています');
            }else{
                $latitude = floatval($lat);
            }
            if ($this->validateParams($lon) === false) {
                throw new Exception('経度に数値以外の指定がされています');
            }else{
                $longitude = floatval($lon);
            }
            // 入力値のValidation(個別)
            if ($latitude < 0 || $latitude > 90) {
                throw new Exception('緯度の数値の範囲が不正です');
            }
            if ($longitude < 0 || $longitude > 180) {
                throw new Exception('経度の数値の範囲が不正です');
            }
        } catch (Exception $e){
            die($e->getMessage());
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
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

    private function validateParams($input)
    {
        // 値が数値型のみ許可
        if (!is_float($input) || !is_int($input)) return false;
        if (preg_match('/^([1-9][0-9]*|0)(.[0-9]+)?$/', $input)) {
            return true; // 正の整数か小数
        }else{
            return false;
        }

    }
    
}

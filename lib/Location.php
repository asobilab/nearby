<?php

namespace Asobilab\Nearby;

use Exception;
use Psr\Log\InvalidArgumentException;

class Location
{

    // GeodeticLatitude (測地緯度) - [一般的な「緯度」]
    protected $latitude;
    // GeodeticLongitude(測地経度)
    protected $longitude;

    /**
     * @param $lat  緯度
     * @param $lon  経度
     */
    public function __construct($lat, $lon)
    {
        $distrust = ['latitude' => $lat, 'longitude' => $lon];
        foreach ($distrust as $key => $val) {
            if ($this->validateParams($val, $key) === false) {
                #[TODO:Hir0TKHS] エラーメッセージの詳細化をお任せします。
                throw new InvalidArgumentException('');
            }
        }

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

    /**
     * 緯度、経度の検証を行う。
     *
     * @param $input
     * @param $use
     * @return bool|mixed
     */
    private function validateParams($input, $use)
    {
        $range = ['latitude' => ['min' => 0.0, 'max' => 90.0],
                  'longitude' => ['min' => 0.0, 'max' => 180]];

        return filter_var($input, FILTER_CALLBACK, ['options' => function($val) use (&$range, &$use) {
            if (filter_var($val, FILTER_VALIDATE_FLOAT) === false) {
                    return false;
            }
            $r = $range[$use];
            if ($val < $r['min'] || $r['max'] < $val) {
                return false;
            }
        }]);
        return true;
    }
}

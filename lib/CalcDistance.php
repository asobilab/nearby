<?php

namespace Asobilab\Nearby;

require 'Location.php';

class CalcDistance
{
    // クラス定数
    // WGS84準拠楕円体
    const EQUATORIAL_RADIUS = 6378137.0;    // 赤道半径
    const OBLATENESS = 0.00335281066474;    // 扁平率
    const POLAR_RADIUS = 6356752.3;         // 極半径 = (赤道半径 - 扁平率) / 赤道半径

    /**
     * ２点間の直線距離を求める（Lambert-Andoyer）
     * @param   Location   $locA   始点緯度経度
     * @param   Location   $locB   終点緯度経度
     * @return  float               距離（m）
     */
    public static function lambertAndoyer(Location $locA, Location $locB)
    {
        // Input Params And Convert Degrees To Radian
        $latA = deg2rad($locA->getLatitude());
        $lonA = deg2rad($locA->getLongitude());
        $latB = deg2rad($locB->getLatitude());
        $lonB = deg2rad($locB->getLongitude());

        // Convert Geodetic Latitude To Parametic Latitude
        $parameticA = atan(self::POLAR_RADIUS / self::EQUATORIAL_RADIUS) * tan($latA);
        $parameticB = atan(self::POLAR_RADIUS / self::EQUATORIAL_RADIUS) * tan($latB);

        // Spherical Distance
        $sphericalDistance = acos(sin($parameticA)*sin($parameticB) + cos($parameticA)*cos($parameticB)*cos($lonA-$lonB));

        // Lambert-Andoyer Correction
        $cosSphericalDistance = cos($sphericalDistance / 2);
        $sinSphericalDistance = sin($sphericalDistance / 2);
        $cosGroup = (sin($sphericalDistance) - $sphericalDistance) * pow(sin($parameticA) + sin($parameticB), 2) / $cosSphericalDistance / $cosSphericalDistance;
        $sinGroup = (sin($sphericalDistance) + $sphericalDistance) * pow(sin($parameticA) - sin($parameticB), 2) / $sinSphericalDistance / $sinSphericalDistance;
        $delta = self::OBLATENESS / 8.0 * ($cosGroup - $sinGroup);

        // Geodetic Distance
        $distance = self::EQUATORIAL_RADIUS * ($sphericalDistance + $delta); // $distance is meter.

        return $distance;
    }

}

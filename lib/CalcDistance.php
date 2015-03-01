<?php

namespace Asobilab\Nearby;

require 'Location.php';

class CalcDistance
{

    /**
     * ２点間の直線距離を求める（Lambert-Andoyer）
     * @param   Location   $locA   始点緯度経度
     * @param   Location   $locB   終点緯度経度
     * @return  float               距離（m）
     */
    public static function lambert(Location $locA, Location $locB)
    {
        // Convert Input Params
        $latA = $locA->getLatitude();
        $lonA = $locA->getLongitude();
        $latB = $locB->getLatitude();
        $lonB = $locA->getLongitude();

        // WGS84準拠楕円体
        $equatorialRadius = 6378137.0;         // 赤道半径
        $oblateness = 1 / 298.257223563; // 扁平率
        $polarRadius = $equatorialRadius * (1.0 - $oblateness);   // 極半径  F = (赤道半径 - 扁平率) / 赤道半径

        // Convert Degrees To Radian
        $latA = deg2rad($latA);
        $lonA = deg2rad($lonA);
        $latB = deg2rad($latB);
        $lonB = deg2rad($lonB);

        // Convert Geodetic Latitude To Parametic Latitude
        $parameticA = atan($polarRadius/$equatorialRadius) * tan($latA);
        $parameticB = atan($polarRadius/$equatorialRadius) * tan($latB);

        // Spherical Distance
        $sphericalDistance = acos(sin($parameticA)*sin($parameticB) + cos($parameticA)*cos($parameticB)*cos($lonA-$lonB));

        // Lambert-Andoyer Correction
        $cosSphericalDistance = cos($sphericalDistance / 2);
        $sinSphericalDistance = sin($sphericalDistance / 2);
        $cosGroup = (sin($sphericalDistance) - $sphericalDistance) * pow(sin($parameticA) + sin($parameticB), 2) / $cosSphericalDistance / $cosSphericalDistance;
        $sinGroup = (sin($sphericalDistance) + $sphericalDistance) * pow(sin($parameticA) - sin($parameticB), 2) / $sinSphericalDistance / $sinSphericalDistance;
        $delta = $oblateness / 8.0 * ($cosGroup - $sinGroup);

        // Geodetic Distance
        $distance = $equatorialRadius * ($sphericalDistance + $delta); // $distance is meter.

        return $distance;
    }
}

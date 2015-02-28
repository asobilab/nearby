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
        $A = 6378137.0;         // 赤道半径
        $F = 1 / 298.257223563; // 扁平率
        $B = $A * (1.0 - $F);   // 極半径  F = (A - B) / A

        // Convert Degrees To Radian
        $latA = deg2rad($latA);
        $lonA = deg2rad($lonA);
        $latB = deg2rad($latB);
        $lonB = deg2rad($lonB);

        // Convert Geodetic Latitude To Parametic Latitude
        $P1 = atan($B/$A) * tan($latA);
        $P2 = atan($B/$A) * tan($latB);

        // Spherical Distance
        $sd = acos(sin($P1)*sin($P2) + cos($P1)*cos($P2)*cos($lonA-$lonB));

        // Lambert-Andoyer Correction
        $cos_sd = cos($sd / 2);
        $sin_sd = sin($sd / 2);
        $c = (sin($sd) - $sd) * pow(sin($P1) + sin($P2), 2) / $cos_sd / $cos_sd;
        $s = (sin($sd) + $sd) * pow(sin($P1) - sin($P2), 2) / $sin_sd / $sin_sd;
        $delta = $F / 8.0 * ($c - $s);

        // Geodetic Distance
        $distance = $A * ($sd + $delta); // $distance is meter.

        return $distance;
    }
}

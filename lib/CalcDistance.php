<?php

namespace Asobilab\Nearby;

use Asobilab\Nearby\Location;

class CalcDistance
{
    // クラス定数
    // GRS80
    const GRS80_A = 6378137.000; // 長半径A
    const GRS80_E2 = 0.00669438002301188; // 離心率e2
    const GRS80_NUMERATOR = 6335439.32708317; // A(1-e2)

    /**
     * ２点間の直線距離を求める
     * get a distance between two points.
     * @param Location $locA 始点緯度経度（測地）
     * @param Location $locB 終点緯度経度（測地）
     * @return float          距離（m）
     */
    public static function getDistance(Location $locA, Location $locB)
    {
        return self::hubeny($locA, $locB);
    }

    private static function hubeny(Location $locA, Location $locB)
    {
        /* cf.
            http://yamadarake.jp/trdi/report000001.html
            http://hp.vector.co.jp/authors/VA002244/yacht/geo.htm
         * */

        $radLat = deg2rad(abs($locA->getLatitude() - $locB->getLatitude()));
        $radLon = deg2rad(abs($locA->getLongitude() - $locB->getLongitude()));
        $radLatAve = deg2rad(($locA->getLatitude() + $locB->getLatitude()) / 2.0);

        $sinAve = sin($radLatAve);
        $denominatorCR = sqrt(1.0 - self::GRS80_E2 * pow($sinAve, 2));// 子午線・卯酉線曲率半径の分母
        $meridian = self::GRS80_NUMERATOR / pow($denominatorCR, 3); // 子午線曲率半径
        $primeVertical = self::GRS80_A / $denominatorCR; // 卯酉線曲率半径

        $distance = sqrt(pow($radLat * $meridian, 2) + pow($radLon * $primeVertical * cos($radLatAve), 2));
        return round($distance, 0, PHP_ROUND_HALF_UP);
    }
}

<?php

namespace Asobilab\Nearby;

use Asobilab\Nearby\Location;

class CalcDistance
{
    #TODO:refactoring const.
    // クラス定数
    // WGS84準拠楕円体
    const EQUATORIAL_RADIUS_WGS84 = 6378137.0;    // 赤道半径 （長半径）
    const FLATTENING_WGS84 = 0.00335281066474;    // 扁平率
    const POLAR_RADIUS_WGS84 = 6356752.3;         // 極半径 （短半径）
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


    /**
     * - Lambert-Andoyer -
     * ２点間の直線距離を求める（Lambert-Andoyer法）
     * get a distance between two points.(Lambert-Andoyer method)
     * @param   Location   $locA   始点緯度経度（測地）
     * @param   Location   $locB   終点緯度経度（測地）
     * @return  float               距離（m）
     */
    private static function lambertAndoyer(Location $locA, Location $locB)
    {
        // Input Params And Convert Degrees To Radian
        $latA = deg2rad($locA->getLatitude());
        $lonA = deg2rad($locA->getLongitude());
        $latB = deg2rad($locB->getLatitude());
        $lonB = deg2rad($locB->getLongitude());

        // Convert Geodetic Latitude To Parametric Latitude
        $latA = self::convertGeoToParaLatitude($latA);
        $latB = self::convertGeoToParaLatitude($latB);

        // Spherical Distance
        $sphericalD = self::getSphericalDistance($latA, $lonA, $latB, $lonB);

        // Lambert-Andoyer Correction
        $delta = self::lambertAndoyerCorrection($latA, $latB, $sphericalD);

        // Geodetic Distance
        $distance = self::EQUATORIAL_RADIUS_WGS84 * ($sphericalD + $delta); // $distance is meter.

        return $distance;
    }

    /**
     * - Lambert-Andoyer -
     * 測地緯度をパラメトリック（化成）緯度に変換する
     * convert Geodetic To Parametric.
     * @param   float   $geoLat   測地緯度
     * @return  float             パラメトリック緯度
     */
    private static function convertGeoToParaLatitude($geoLat)
    {
        return atan(self::POLAR_RADIUS_WGS84 / self::EQUATORIAL_RADIUS_WGS84) * tan($geoLat);
    }

    /**
     * - Lambert-Andoyer -
     * @param float $paraLatA A地点のパラメトリック緯度
     * @param float $geoLonA A地点の測地経度
     * @param float $paraLatB B地点のパラメトリック緯度
     * @param float $geoLonB B地点の測地経度
     * @return float       球面上の距離
     */
    private static function getSphericalDistance($paraLatA, $geoLonA, $paraLatB, $geoLonB)
    {
        return acos(sin($paraLatA)*sin($paraLatB) + cos($paraLatA)*cos($paraLatB)*cos($geoLonA-$geoLonB));
    }

    /**
     * - Lambert-Andoyer -
     * @param float $paraLatA A地点のパラメトリック緯度
     * @param float $paraLatB B地点のパラメトリック緯度
     * @param float $sphericalD 球面上の距離
     * @return float            補正
     */
    private static function lambertAndoyerCorrection($paraLatA, $paraLatB, $sphericalD)
    {
        $cosSphericalD = cos($sphericalD / 2);
        $sinSphericalD = sin($sphericalD / 2);
        $cosSet = (sin($sphericalD) - $sphericalD) * pow(sin($paraLatA) + sin($paraLatB), 2)
                  / $cosSphericalD / $cosSphericalD;
        $sinSet = (sin($sphericalD) + $sphericalD) * pow(sin($paraLatA) - sin($paraLatB), 2)
                  / $sinSphericalD / $sinSphericalD;
        return self::FLATTENING_WGS84 / 8.0 * ($cosSet - $sinSet);
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

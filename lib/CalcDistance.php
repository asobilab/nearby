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
     * ２点間の直線距離を求める
     * get a distance between two points.
     * @param Location $locA 始点緯度経度（測地）
     * @param Location $locB 終点緯度経度（測地）
     * @return float          距離（m）
     */
    public static function getDistance(Location $locA, Location $locB)
    {
        return self::lambertAndoyer($locA, $locB);
    }

    /**
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
        $distance = self::EQUATORIAL_RADIUS * ($sphericalD + $delta); // $distance is meter.

        return $distance;
    }

    /**
     * 測地緯度をパラメトリック（化成）緯度に変換する
     * convert Geodetic To Parametric.
     * @param   float   $geoLat   測地緯度
     * @return  float             パラメトリック緯度
     */
    private static function convertGeoToParaLatitude($geoLat)
    {
        return atan(self::POLAR_RADIUS / self::EQUATORIAL_RADIUS) * tan($geoLat);
    }

    /**
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
        return self::OBLATENESS / 8.0 * ($cosSet - $sinSet);
    }
}

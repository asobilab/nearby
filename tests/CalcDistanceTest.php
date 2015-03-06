<?php

/**
 *  @package    Asobiab\Nearby\Test
 *  @author     Hir0TKHS <ht.activities@gmail.com>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/asobilab/nearby
 */

namespace Asobilab\Nearby\Test;

use Asobilab\Nearby\Nearby;
use Asobilab\Nearby\Location;
use Asobilab\Nearby\CalcDistance;

class CalcDistanceTest extends BaseUnit
{
    public function testCalcDistance()
    {
        $locationA = new Location(35.65500, 139.74472); // 東京
        $locationB = new Location(36.10056, 140.09111); // 筑波
        $result = CalcDistance::getDistance($locationA, $locationB);
        $this->assertEquals($result, 58502);
    }

    public function testCalcDistanceFailure()
    {
        $locationA = new Location(35.65500, 139.74472);
        $locationB = new Location(36.10056, 140.09111);
        $result = CalcDistance::getDistance($locationA, $locationB);
        $this->assertNotEquals($result, 11111.11);
    }


    public function locationAndDistanceProvider()
    {
        return [[35.65500, 139.74472, 36.10056, 140.09111, 58502]];
    }

    /**
     * @dataProvider    locationAndDistanceProvider
     */
    public function testCalcDistanceWithArguments($latA, $lonA, $latB, $lonB, $distance)
    {
        $locationA = new Location($latA, $lonA);
        $locationB = new Location($latB, $lonB);
        $calcResult = CalcDistance::getDistance($locationA, $locationB);

        $diff = abs($calcResult - $distance);
        $permissibleRange = 0.01; // 1% is permitted.

        $this->assertLessThanOrEqual($calcResult * $permissibleRange, $diff);
    }
}

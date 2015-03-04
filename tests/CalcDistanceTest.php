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

Class CalcDistanceTest extends BaseUnit
{
    public function testCalcDistance()
    {
        $locationA = new Location(35.54804,139.724778);
        $locationB = new Location(35.554694,139.732664);
        $result = CalcDistance::getDistance($locationA,$locationB);
        $this->assertEquals($result,1148.2904126236);
    }

    public function testCalcDistanceFailure()
    {
        $locationA = new Location(35.54804,139.724778);
        $locationB = new Location(35.554694,139.732664);
        $result = CalcDistance::getDistance($locationA,$locationB);
        $this->assertNotEquals($result,11111.11);
    }


    public function locationAndDistanceProvider()
    {
        return [[35.54804,139.724778,35.554694,139.732664,1148.2904126236]];
    }

    /**
     * @dataProvider    locationAndDistanceProvider
     */
    public function testCalcDistanceWithArguments($latA,$lonA,$latB,$lonB,$distance)
    {
        $locationA = new Location($latA,$lonA);
        $locationB = new Location($latB,$lonB);
        $calcResult = CalcDistance::getDistance($locationA,$locationB);
        $this->assertEquals($calcResult,$distance);
    }
}

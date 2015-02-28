<?php

/**
 *  @package    Asobiab\Nearby\Test
 *  @author     Coilo <coilo.dev@gmail.com>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/asobilab/nearby
 */

namespace Asobilab\Nearby\Test;

use Asobilab\Nearby\Nearby;
use Asobilab\Nearby\Location;

/**
 *  Nearby testcase.
 */
class NearbyTest extends BaseUnit
{
    public function testVersion()
    {
        $this->assertEquals("0.0.1", Nearby::VERSION);
    }
    
    /**
     * @dataProvider    locationProvider
     */
    public function testLocation($latitude, $longitude)
    {
        $location = new Location($latitude, $longitude);
        $actual = $location->getCoordinate();
        $this->assertEquals($latitude, $actual['latitude']);
        $this->assertEquals($longitude, $actual['longitude']);
    }

    public function locationProvider()
    {
        return [[35.65858, 139.745433],[0.0, 0.0], [90.0, 180.0]];
    }

    /**
     *  @dataProvider      locationFailureProvider
     *  @expectedException InvalidArgumentException
     */
    public function testLocationFailure($latitude, $longitude)
    {
        $location = new Location($latitude, $longitude);
    }

    public function locationFailureProvider()
    {
        return [[95.0, 120.0], [ 40.0, 210.0]];
    }
}

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

/**
 *  Nearby testcase.
 */
class NearbyTest extends  \PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $this->assertEquals("0.0.1", Nearby::VERSION);
    }
}

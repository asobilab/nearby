<?php

/**
 *  @package    Asobilab\Nearby\Test
 *  @author     Coilo <coilo.dev@gmail.com>
 *  @license    MIT License
 *  @version    1.0.0
 *  @link       https://github.com/asobilab/nearby
 */

namespace Asobilab\Nearby\Test;

/**
 *  BaseUnit class.
 */
abstract class BaseUnit extends \PHPUnit_Framework_TestCase
{
    /**
     *  @var object $instance
     */
    protected $instance = null;

    /**
     *  @var string $name
     */
    protected $name = null;

    /**
     *  Setup for the testcase.
     *
     *  @access protected
     */
    protected function setUp()
    {
        $this->name = get_class($this->instance);
    }

    /**
     *  Get a random number.
     *
     *  @access protected
     */
    protected function sampleInt()
    {
        return mt_rand(1, 10000);
    }

    /**
     *  Get a random string.
     *
     *  @access protected
     */
    protected function sampleString($number = null)
    {
        $method = "";
        $until  = $number ?: mt_rand(3, 8);
        static $characters = "abcdefghijklmnopqrstuvwxyz";
        for ($length = 1; $length <= $until; ++$length) {
            $method .= $characters[mt_rand(0, 25)];
        }
        return $method;
    }
}

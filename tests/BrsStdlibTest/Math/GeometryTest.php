<?php

namespace BrsStdlibTest\Math;

use Brs\Stdlib\Math\Geometry;

class GeometryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testRotatePoint()
    {
        $this->assertEquals(['x' => 10, 'y' => 0], Geometry::rotatePoint(0, 0, 5, 5, 90));
        $this->assertEquals(['x' => 10, 'y' => 10], Geometry::rotatePoint(0, 0, 5, 5, 180));
        $this->assertEquals(['x' => 0, 'y' => 0], Geometry::rotatePoint(0, 0, 5, 5, 360));
        $this->assertEquals(['x' => 10, 'y' => 0], Geometry::rotatePoint(0, 0, 5, 5, 450));
    }
}
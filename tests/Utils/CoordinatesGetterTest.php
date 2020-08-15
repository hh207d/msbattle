<?php

namespace App\Tests\Utils;

use App\Entity\Placement;
use App\Entity\Ship;
use App\Entity\Shiptype;
use App\Utils\CoordinatesGetter;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class CoordinatesGetterTest extends TestCase
{

    /**
     * @dataProvider providerTestGetPointsToUpdate
     * @param $shipLength
     * @param $orientation
     * @param $x
     * @param $y
     * @param $expected
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetPointsToUpdate($shipLength, $orientation, $x, $y, $expected)
    {
        $exampleShipType = new Shiptype();
        $exampleShipType->setLength($shipLength);

        $exampleShip = new Ship();
        $exampleShip->setType($exampleShipType);

        $placement = new Placement();
        $placement->setOrientation($orientation);
        $placement->setShip($exampleShip);
        $placement->setShip($exampleShip);
        $placement->setXcoord($x);
        $placement->setYcoord($y);

        $coordinatesGetter = new CoordinatesGetter();
        $coords = $coordinatesGetter->getPointsToUpdate($placement);

        $this->assertEquals($expected, $coords);

    }

    public function providerTestGetPointsToUpdate()
    {
        return [
            [
                'shipLength' => 3,
                'orientation' => 'HORIZONTAL',
                'x' => 0,
                'y' => 0,
                'expected' => [[0, 0],[1, 0],[2, 0]]
            ],
            [
                'shipLength' => 3,
                'orientation' => 'VERTICAL',
                'x' => 0,
                'y' => 0,
                'expected' => [[0, 0],[0, 1],[0, 2]]
            ],
            [
                'shipLength' => 2,
                'orientation' => 'HORIZONTAL',
                'x' => 3,
                'y' => 3,
                'expected' => [[3, 3],[4, 3]]
            ],
            [
                'shipLength' => 2,
                'orientation' => 'VERTICAL',
                'x' => 3,
                'y' => 3,
                'expected' => [[3, 3],[3, 4]]
            ],

        ];
    }
}

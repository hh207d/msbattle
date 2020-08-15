<?php

namespace App\Tests\Utils;

use App\Entity\Game;
use App\Entity\Placement;
use App\Entity\Ship;
use App\Entity\Shiptype;
use App\Helper\Orientation;
use App\Utils\InsideOceanChecker;
use PHPUnit\Framework\TestCase;

class InsideOceanCheckerTest extends TestCase
{

    /**
     * @dataProvider providerTestCheck
     * @param $sizeX
     * @param $sizeY
     * @param $shipLength
     * @param $orientation
     * @param $x
     * @param $y
     * @param $expected
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCheck($sizeX, $sizeY, $shipLength, $orientation, $x, $y, $expected)
    {
        $game = new Game();
        $game->setSizeX($sizeX);
        $game->setSizeY($sizeY);

        $shipType = new Shiptype();
        $shipType->setLength($shipLength);

        $ship = new Ship();
        $ship->setType($shipType);


        $placement = new Placement();
        $placement->setGame($game);
        $placement->setXcoord($x);
        $placement->setYcoord($y);
        $placement->setOrientation($orientation);
        $placement->setShip($ship);

        $checker = new InsideOceanChecker();
        $isPlaceable = $checker->check($placement);

        $this->assertEquals($expected, $isPlaceable);
    }

    public function providerTestCheck()
    {
        return [
            [
                'sizeX' => 8,
                'sizeY' => 8,
                'shipLength' => 4,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'sizeX' => 2,
                'sizeY' => 2,
                'shipLength' => 4,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => false
            ],
            [
                'sizeX' => 2,
                'sizeY' => 2,
                'shipLength' => 4,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => false
            ],
            [
                'sizeX' => 3,
                'sizeY' => 3,
                'shipLength' => 3,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'sizeX' => 3,
                'sizeY' => 3,
                'shipLength' => 3,
                'orientation' => Orientation::VERTICAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'sizeX' => 8,
                'sizeY' => 1,
                'shipLength' => 3,
                'orientation' => Orientation::VERTICAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
        ];
    }
}

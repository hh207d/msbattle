<?php

namespace App\Tests\Utils;

use App\Entity\Game;
use App\Entity\Placement;
use App\Entity\Ship;
use App\Entity\Shiptype;
use App\Helper\Orientation;
use App\Utils\InsideOceanChecker;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class InsideOceanCheckerTest extends TestCase
{

    /**
     * @dataProvider providerTestCheck
     * @param $height
     * @param $width
     * @param $shipLength
     * @param $orientation
     * @param $x
     * @param $y
     * @param $expected
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCheck($height, $width, $shipLength, $orientation, $x, $y, $expected)
    {
        $game = new Game();
        $game->setHeight($height);
        $game->setWidth($width);

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
                'height' => 8,
                'width' => 8,
                'shipLength' => 4,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'height' => 2,
                'width' => 2,
                'shipLength' => 4,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => false
            ],
            [
                'height' => 2,
                'width' => 2,
                'shipLength' => 4,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => false
            ],
            [
                'height' => 3,
                'width' => 3,
                'shipLength' => 3,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'height' => 3,
                'width' => 3,
                'shipLength' => 3,
                'orientation' => Orientation::VERTICAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'height' => 8,
                'width' => 1,
                'shipLength' => 3,
                'orientation' => Orientation::VERTICAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
            [
                'height' => 8,
                'width' => 1,
                'shipLength' => 3,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => false
            ],
        ];
    }
}

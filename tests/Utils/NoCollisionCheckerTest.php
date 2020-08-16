<?php

namespace App\Tests\Utils;

use App\Entity\Cell;
use App\Entity\Game;
use App\Entity\Placement;
use App\Entity\Ship;
use App\Entity\Shiptype;
use App\Helper\Orientation;
use App\Utils\CoordinatesGetter;
use App\Utils\NoCollisionChecker;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class NoCollisionCheckerTest extends TestCase
{

    /**
     * @dataProvider providerTestCheck
     * @param ArrayCollection $occupiedCellCoordinates
     * @param int $shipLength
     * @param Orientation $orientation
     * @param int $xCoordinate
     * @param int $yCoordinate
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCheck(ArrayCollection $occupiedCellCoordinates, int $shipLength, string $orientation, int $xCoordinate, int $yCoordinate, bool $expected)
    {
        /*
        $occupiedCellCoordinates = [[1, 0]];
        $shipLength = 2;
        $orientation = Orientation::HORIZONTAL;
        $xCoordinate = 0;
        $yCoordinate = 0;
        $expected = false;
*/
        $gameMock = $this->prepareGame($occupiedCellCoordinates);
        $placement = $this->preparePlacement($gameMock, $shipLength, $orientation, $xCoordinate, $yCoordinate);

        $noCollisionChecker = new NoCollisionChecker();
        $this->assertEquals($expected, $noCollisionChecker->check($placement));

    }

    public function prepareGame($occupiedCellCoordinates)
    {
        $alreadyOccupiedCells = new ArrayCollection();
        foreach ($occupiedCellCoordinates as $occupiedCellCoordinate)
        {
            $alreadyOccupiedCell = new Cell();
            $alreadyOccupiedCell->setXCoordinate($occupiedCellCoordinate[0]);
            $alreadyOccupiedCell->setYCoordinate($occupiedCellCoordinate[1]);

            $alreadyOccupiedCells->add($alreadyOccupiedCell);
        }

        $gameMock = $this->getMockBuilder(Game::class )
            ->setMethods(['getCells'])
            ->getMock();

        $gameMock->expects($this->once())->method('getCells')->will($this->returnValue($alreadyOccupiedCells));

        return $gameMock;
    }

    public function preparePlacement($gameMock, $shipLength, $orientation, $xCoordinate, $yCoordinate)
    {
        $shipTypeToPlace = new Shiptype();
        $shipTypeToPlace->setLength($shipLength);

        $shipToPlace = new Ship();
        $shipToPlace->setType($shipTypeToPlace);

        $placement = new Placement();
        $placement->setGame($gameMock);
        $placement->setShip($shipToPlace);
        $placement->setOrientation($orientation);
        $placement->setXcoord($xCoordinate);
        $placement->setYcoord($yCoordinate);

        return $placement;
    }


    public function providerTestCheck()
    {
        return [
            [
                'occupiedCellCoordinates' => new ArrayCollection([[1, 0]]),
                'shipLength' => 2,
                'orientation' => Orientation::HORIZONTAL,
                'x' => 0,
                'y' => 0,
                'expected' => false
            ],
            [
                'occupiedCellCoordinates' => new ArrayCollection([[1, 0]]),
                'shipLength' => 2,
                'orientation' => Orientation::VERTICAL,
                'x' => 0,
                'y' => 0,
                'expected' => true
            ],
        ];
    }
}

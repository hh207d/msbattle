<?php


namespace App\Utils;


use App\Entity\Placement;
use App\Helper\Orientation;

/**
 * Class CoordinatesGetter
 * @package App\Utils
 */
class CoordinatesGetter
{
    /**
     * @param Placement $placement
     * @return array
     */
    public function getPointsToUpdate(Placement $placement)
    {
        $result = [];
        $axisToCountUpIsX = $placement->getOrientation() === Orientation::HORIZONTAL;
        $shipLength = $placement->getShip()->getType()->getLength();
        $xCoord = $placement->getXcoord();
        $yCoord = $placement->getYcoord();
        for($shipLengthIndex = 0; $shipLengthIndex < $shipLength; $shipLengthIndex++)
        {
            if($axisToCountUpIsX)
            {
                $xValue = $xCoord + $shipLengthIndex;
                $yValue = $yCoord;
            }
            else
            {
                $xValue = $xCoord;
                $yValue = $yCoord + $shipLengthIndex;
            }
            $result[$shipLengthIndex] = [$xValue, $yValue];
        }

        return $result;
    }
}
<?php


namespace App\Utils;


use App\Entity\Placement;


class CoordinatesGetter
{
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
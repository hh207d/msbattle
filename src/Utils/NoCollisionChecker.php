<?php


namespace App\Utils;


use App\Entity\Placement;

class NoCollisionChecker
{
    public function check(Placement $placement)
    {
        $game = $placement->getGame();
        $cells = $game->getCells();

        $cellArr = [];
        foreach ($cells as $cell)
        {
            $cellArr[] = [$cell->getXCoordinate(), $cell->getYCoordinate()];
        }

        $coordinatesGetter = new CoordinatesGetter();
        $coordinatesToUpdate = $coordinatesGetter->getPointsToUpdate($placement);

        foreach ($coordinatesToUpdate as $coord)
        {
            foreach ($cellArr as $possiblyOccupiedCell)
            {
                if($coord[0] === $possiblyOccupiedCell[0] && $coord[1] === $possiblyOccupiedCell[1])
                {
                    return false;
                }
            }
        }
        return true;



    }
}
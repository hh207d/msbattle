<?php


namespace App\Utils;


use App\Entity\Placement;

class InsideOceanChecker
{
    /**
     * @param Placement $placement
     * @return bool
     */
    public function check(Placement $placement)
    {
        $game = $placement->getGame();
        $xSize = $game->getHeight();
        $ySize = $game->getWidth();

        $coordinatesGetter = new CoordinatesGetter();
        $coordinatesToUpdate = $coordinatesGetter->getPointsToUpdate($placement);

        foreach ($coordinatesToUpdate as $coord)
        {
            if($coord[0] < 0 || $coord[0] > ($xSize - 1))
            {
                return false;
            }
            if($coord[1] < 0 || $coord[0] > ($ySize -1))
            {
                return false;
            }
        }

        return true;
    }
}
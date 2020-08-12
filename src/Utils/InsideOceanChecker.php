<?php


namespace App\Utils;


use App\Entity\Placement;

class InsideOceanChecker
{
    public function check(Placement $placement)
    {
        $game = $placement->getGame();
        $xSize = $game->getSizeX();
        $ySize = $game->getSizeY();



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
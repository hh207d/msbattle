<?php


namespace App\Helper;


abstract class ValidatorMessage
{
    const PLACEMENT_NOT_IN_OCEAN = "The ship is not placed fully inside the ocean";
    const PLACEMENT_COLLIDES_WITH_OTHER_SHIP = "Ship does collide with other object..";
    const PLACEMENT_USER_NOT_MATCHING = "User does not match.";
}
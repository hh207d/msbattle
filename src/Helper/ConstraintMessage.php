<?php


namespace App\Helper;


abstract class ConstraintMessage
{
    const GAME_NOT_OWNER = 'You are not the owner of this game';
    const GAME_NOT_IN_BATTLE_MODE = 'This games is not in battle mode';
    const GAME_NOT_IN_PLACEMENT_MODE = 'This games is not in placement mode';
    const SHIP_IS_NOT_DOCKED = 'This ship is not docked';
    const SHIP_IS_NOT_YOURS = 'This ship does not belong to you';
    const COORDINATES_ALREADY_BOMBED = 'This field has been bombed already';
    const TURN_HAS_NO_VALID_COORDS = 'These coordinates are invalid';
    const NO_VALID_ORIENTATION = 'This is not a valid orientation.';

}
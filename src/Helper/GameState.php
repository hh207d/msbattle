<?php


namespace App\Helper;


abstract class GameState
{
    const STATE_STARTED = 'STATE_STARTED';
    const STATE_BATTLE = 'STATE_BATTLE';
    const STATE_FINISHED = 'STATE_FINISHED';
}
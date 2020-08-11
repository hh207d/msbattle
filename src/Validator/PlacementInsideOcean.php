<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlacementInsideOcean extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The ship is not placed fully inside the ocean';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }



}

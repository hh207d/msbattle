<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlacementNoCollision extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Ship does collide with other object.. "{{ value }}".';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

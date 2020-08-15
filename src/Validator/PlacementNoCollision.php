<?php

namespace App\Validator;

use App\Helper\ValidatorMessage;
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
    public $message = ValidatorMessage::PLACEMENT_COLLIDES_WITH_OTHER_SHIP;

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

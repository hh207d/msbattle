<?php

namespace App\Validator;

use App\Helper\ValidatorMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlacementNoCollision extends Constraint
{
    /**
     * @var string
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

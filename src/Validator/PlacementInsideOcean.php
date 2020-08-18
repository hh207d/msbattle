<?php

namespace App\Validator;

use App\Helper\ValidatorMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlacementInsideOcean extends Constraint
{
    /**
     * @var string
     */
    public $message = ValidatorMessage::PLACEMENT_NOT_IN_OCEAN;

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

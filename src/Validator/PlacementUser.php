<?php

namespace App\Validator;

use App\Helper\ValidatorMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlacementUser extends Constraint
{
    /**
     * @var string
     */
    public $message = ValidatorMessage::PLACEMENT_USER_NOT_MATCHING;
}

<?php

namespace App\Validator;

use App\Entity\Placement;
use App\Utils\NoCollisionChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PlacementNoCollisionValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint PlacementNoCollision */

        /** @var Placement $value */
        if (null === $value || '' === $value) {
            return;
        }

        $checker = new NoCollisionChecker();
        if ($checker->check($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}

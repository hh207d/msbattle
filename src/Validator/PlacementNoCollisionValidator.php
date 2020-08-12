<?php

namespace App\Validator;

use App\Utils\NoCollisionChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PlacementNoCollisionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint PlacementNoCollision */

        if (null === $value || '' === $value) {
            return;
        }
        $checker = new NoCollisionChecker();
        if ($checker->check($value)) {
            return;
        }
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}

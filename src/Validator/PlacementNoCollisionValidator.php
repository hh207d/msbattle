<?php

namespace App\Validator;

use App\Utils\NoCollisionChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PlacementNoCollisionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\PlacementNoCollision */

        if (null === $value || '' === $value) {
            return;
        }
        $checker = new NoCollisionChecker();
        if($checker->check($value))
        {
            return;
        }
        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}

<?php

namespace App\Validator;

use App\Entity\Placement;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PlacementUserValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint PlacementUser */

        /** @var Placement $value */
        if (null === $value || '' === $value) {
            return;
        }
        $user = $value->getUser();
        if ($user === $value->getShip()->getUser() && $user === $value->getGame()->getUser()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}

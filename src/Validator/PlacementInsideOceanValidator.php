<?php

namespace App\Validator;

use App\Entity\Placement;
use App\Utils\InsideOceanChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PlacementInsideOceanValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint PlacementInsideOcean */

        /** @var Placement $value */
        if (null === $value || '' === $value) {
            return;
        }
        $checker = new InsideOceanChecker();
        if ($checker->check($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}

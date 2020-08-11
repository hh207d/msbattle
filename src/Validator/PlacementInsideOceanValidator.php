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
        /* @var $constraint \App\Validator\PlacementInsideOcean */


        /** @var Placement $value */
        if (null === $value || '' === $value) {
            return;
        }
        $checker = new InsideOceanChecker();
        if($checker->check($value))
        {
            return;
        }


        // placement
        // game_id, ship_id, user_id, xcoord, ycoord, orientation

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }


}

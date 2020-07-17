<?php


namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotStartingWithZeroValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof NotStartingWithZero) {
            throw new UnexpectedTypeException($constraint, NotStartingWithZero::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (strpos($value, '0') === 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode('PWZ_STARTS_WITH_ZERO')
                ->addViolation();
        }
    }
}

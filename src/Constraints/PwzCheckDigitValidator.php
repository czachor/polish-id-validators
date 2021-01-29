<?php


namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PwzCheckDigitValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PwzCheckDigit) {
            throw new UnexpectedTypeException($constraint, PwzCheckDigit::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (preg_match('#[^0-9]#', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode('INVALID_CHECK_DIGIT')
                ->addViolation();

            return;
        }

        $original = $value;
        $check_digit = (int) $value[0];
        $value = substr($value, 1); // remove check digit
        $digits = str_split($value);

        $total = array_map(
            static function ($x, $y) {
                return $x * $y;
            },
            $digits,
            range(1, 6)
        );

        $sum = array_sum($total);
        $mod = $sum % 11;

        if ($mod !== $check_digit) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $original)
                ->setCode('INVALID_CHECK_DIGIT')
                ->addViolation();
        }
    }
}

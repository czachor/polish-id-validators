<?php

namespace Czachor\PolishIdValidators\Constraints;

use IsoCodes\Gtin8;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PwzfDigitsValidator extends ConstraintValidator
{
    public const MIN_DISTRICT_RANGE = 1;
    public const MAX_DISTRICT_RANGE = 22;

    /**
     * @var string[]
     */
    private $valid_chamber_digits = [];
    /**
     * @var string
     */
    private $value;
    /**
     * @var bool
     */
    private $is_error = false;

    public function __construct()
    {
        $this->createValidLocalChambersArray();
    }

    /**
     * range 01-22
     * @see: https://www.nia.org.pl/dat/magazyn/biuletyn_IV_07_2005.pdf
     */
    private function createValidLocalChambersArray(): void
    {
        $this->valid_chamber_digits = array_map(
            static function ($item) {
                return str_pad($item, 2, '0', STR_PAD_LEFT);
            },
            range(self::MIN_DISTRICT_RANGE, self::MAX_DISTRICT_RANGE)
        );
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PwzfDigits) {
            throw new UnexpectedTypeException($constraint, PwzfDigits::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $this->value = $value;

        $this->checkLocalChamberDigists();
        $this->validateGtin8();

        if ($this->is_error) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $this->value)
                ->addViolation();
        }
    }

    private function checkLocalChamberDigists(): void
    {
        $chamber_digits = substr($this->value, 0, 2);

        if (!in_array($chamber_digits, $this->valid_chamber_digits, true)) {
            $this->is_error = true;
        }
    }

    private function validateGtin8(): void
    {
        if (!Gtin8::check($this->value, 8)) {
            $this->is_error = false;
        }
    }
}

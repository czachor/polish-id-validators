<?php


namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PwzpDigitsValidator extends ConstraintValidator
{
    /**
     * @var string[]
     */
    private $valid_ending_letters = ['A', 'P'];
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
     * @see: https://nipip.pl/uchwala-nr-320-vii-2018-nrpip-z-dnia-12-wrzesnia-2018-r-w-sprawie-trybu-postepowania-dotyczacego-stwierdzania-i-przyznawania-prawa-wykonywania-zawodu-pielegniarki-i-zawodu-poloznej-oraz-sposobu-prow/
     */
    private function createValidLocalChambersArray(): void
    {
        $this->valid_chamber_digits = array_map(
            static function ($item) {
                return str_pad($item, 2, '0', STR_PAD_LEFT);
            },
            range(1, 45)
        );
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PwzpDigits) {
            throw new UnexpectedTypeException($constraint, PwzpDigits::class);
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

        $this->checkLastChar();
        $this->checkLocalChamberDigists();
        $this->checkDigitsBetween();

        if ($this->is_error) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $this->value)
                ->addViolation();
        }
    }

    private function checkLastChar(): void
    {
        $last_char = strtoupper(substr($this->value, -1)); // uppercase for validation purposes

        if (!in_array($last_char, $this->valid_ending_letters, true)) {
            $this->is_error = true;
        }
    }

    private function checkDigitsBetween(): void
    {
        $digits_between = substr($this->value, 2, 5);

        if (!is_numeric($digits_between)) {
            $this->is_error = true;
        }
    }

    private function checkLocalChamberDigists(): void
    {
        $chamber_digits = substr($this->value, 0, 2);

        if (!in_array($chamber_digits, $this->valid_chamber_digits, true)) {
            $this->is_error = true;
        }
    }
}

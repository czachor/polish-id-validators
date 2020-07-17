<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Entities;


use Czachor\PolishIdValidators\Constraints\PwzpDigits;
use Czachor\PolishIdValidators\EntityInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class PwzpipEntity
 * Prawo Wykonywania Zawodu Pielęgniarki lub Położnej
 * @see https://nipip.pl/weryfikacja-pwz/
 */
class PwzpEntity implements EntityInterface
{
    public const ID_LENGTH = 8;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(
            'value',
            new Length(
                [
                    'min' => self::ID_LENGTH,
                    'max' => self::ID_LENGTH,
                    'maxMessage' => 'max.message',
                    'minMessage' => 'min.message',
                    'exactMessage' => 'exact.message',
                    'charsetMessage' => 'charset.message'
                ]
            )
        );

        $metadata->addPropertyConstraint('value', new PwzpDigits());
    }
}

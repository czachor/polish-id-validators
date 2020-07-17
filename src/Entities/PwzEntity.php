<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Entities;


use Czachor\PolishIdValidators\Constraints\NotStartingWithZero;
use Czachor\PolishIdValidators\Constraints\PwzCheckDigit;
use Czachor\PolishIdValidators\EntityInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PwzEntity
 * Numer Prawa Wykonywania Zawodu Lekarza (medical licence ID in Poland)
 * @see https://nil.org.pl/rejestry/centralny-rejestr-lekarzy/zasady-weryfikowania-nr-prawa-wykonywania-zawodu
 */
class PwzEntity implements EntityInterface
{
    public const ID_LENGTH = 7;

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

        $metadata->addPropertyConstraint('value', new NotStartingWithZero());
        $metadata->addPropertyConstraint(
            'value',
            new Assert\Type(
                [
                    'type' => 'string',
                    'message' => 'type.not.string',
                ]
            )
        );
        $metadata->addPropertyConstraint('value', new PwzCheckDigit());
    }
}

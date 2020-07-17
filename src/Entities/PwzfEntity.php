<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Entities;


use Czachor\PolishIdValidators\Constraints\PwzfDigits;
use Czachor\PolishIdValidators\EntityInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PwzfEntity
 * Prawo Wykonywania Zawodu Farmaceuty
 * @see https://crf.rejestrymedyczne.csioz.gov.pl/
 * @see https://www.nia.org.pl/dat/magazyn/biuletyn_IV_07_2005.pdf
 * @see
 */
class PwzfEntity implements EntityInterface
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

        $metadata->addPropertyConstraint('value', new Assert\Type(
            [
                'type' => 'numeric',
                'message' => 'type.not.numeric',
            ]
        ));
        $metadata->addPropertyConstraint('value', new PwzfDigits());
    }
}

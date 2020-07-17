<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Entities;


use Czachor\PolishIdValidators\EntityInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PwzdlEntity
 * Numer Prawa Wykonywania Zawodu Diagnosty Laboratoryjnego (laboratory diagnostician ID in Poland)
 * Only simple "is numeric" validation. PWZDL ID are simple sequential numbers.
 */
class PwzdlEntity implements EntityInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('value', new Assert\Type(
            [
                'type' => 'numeric',
                'message' => 'type.not.numeric',
            ]
        ));
        $metadata->addPropertyConstraint('value', new Assert\Type(
            [
                'type' => 'string',
                'message' => 'type.not.string',
            ]
        ));
    }
}

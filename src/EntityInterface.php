<?php


namespace Czachor\PolishIdValidators;


use Symfony\Component\Validator\Mapping\ClassMetadata;

interface EntityInterface
{
    public function __construct($value);

    public static function loadValidatorMetadata(ClassMetadata $metadata): void;
}

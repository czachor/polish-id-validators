<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class PwzCheckDigit extends Constraint
{
    public $message = 'pwz.not.valid';
}

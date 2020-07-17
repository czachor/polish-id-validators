<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class PwzfDigits extends Constraint
{
    public $message = 'pwzf.not.valid';
}

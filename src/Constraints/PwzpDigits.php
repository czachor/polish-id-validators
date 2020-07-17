<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class PwzpDigits extends Constraint
{
    public $message = 'pwzp.not.valid';
}

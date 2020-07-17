<?php

declare(strict_types=1);

namespace Czachor\PolishIdValidators\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class NotStartingWithZero extends Constraint
{
    public $message = 'pwz.cannot.start.with.zero';
}

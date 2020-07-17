<?php

declare(strict_types=1);

namespace Czachor\Tests\PolishIdValidators;


use Czachor\PolishIdValidators\Entities\PwzEntity;
use Czachor\PolishIdValidators\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidatorTest extends TestCase
{
    public function testValidator(): void
    {
        $the_id = new PwzEntity('1234567');
        $violations = Validator::validate($the_id);
        $this->assertInstanceOf(ConstraintViolationListInterface::class, $violations);
    }
}

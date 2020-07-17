<?php

declare(strict_types=1);

namespace Czachor\Tests\PolishIdValidators;


use Czachor\PolishIdValidators\Entities\PwzdlEntity;
use Czachor\PolishIdValidators\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;

final class PwzdlEntityTest extends TestCase
{
    public function testPwzdlEmpty(): void
    {
        $the_id = new PwzdlEntity('');
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->assertSame('This value should be of type numeric.', $violation->getMessage());
            }
        }
    }

    public function testPwzdlInt(): void
    {
        $the_id = new PwzdlEntity(1234567);
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->assertSame('This value should be of type string.', $violation->getMessage());
            }
        }
    }

    public function testValidPwzdl(): void
    {
        $the_id = new PwzdlEntity('1443');
        $violations = Validator::validate($the_id);

        $this->assertSame(0, $violations->count());
    }
}

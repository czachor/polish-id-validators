<?php

declare(strict_types=1);

namespace Czachor\Tests\PolishIdValidators;


use Czachor\PolishIdValidators\Constraints\PwzpDigits;
use Czachor\PolishIdValidators\Entities\PwzpEntity;
use Czachor\PolishIdValidators\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;

final class PwzpEntityTest extends TestCase
{
    public function testPwzpEmpty(): void
    {
        $the_id = new PwzpEntity('');
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->assertSame(
                    'ID value should have exactly ' . PwzpEntity::ID_LENGTH . ' characters.',
                    $violation->getMessage()
                );
            }
        }
    }

    public function testPwzpTooShort(): void
    {
        $the_id = new PwzpEntity('1234');
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzpEntity::ID_LENGTH . ' characters.') {
                    $this->assertTrue(true);

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzpTooLong(): void
    {
        $the_id = new PwzpEntity('1234567890');
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzpEntity::ID_LENGTH . ' characters.') {
                    $this->assertTrue(true);

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzpInt(): void
    {
        $the_id = new PwzpEntity(12345678);
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->assertSame('This value should be of type string.', $violation->getMessage());
            }
        }
    }

    public function testValidPwzpId(): void
    {
        $ids = ['0412345A', '2012345P', '1234567A'];

        foreach ($ids as $id) {
            $the_id = new PwzpEntity($id);
            $violations = Validator::validate($the_id);

            $this->assertSame(0, $violations->count());
        }
    }

    public function testOutOfRangeChamberPwzpId(): void
    {
        // first two digits must be in range 00-45
        $ids = ['0012345A', '9912345P', '4634567A'];

        foreach ($ids as $id) {
            $the_id = new PwzpEntity($id);
            $violations = Validator::validate($the_id);

            $this->assertSame(1, $violations->count());
        }
    }

    public function testInvalidLastCharPwzpId(): void
    {
        $the_id = new PwzpEntity('1234567X');
        $violations = Validator::validate($the_id);

        $this->assertSame(1, $violations->count());

        $constraint = new PwzpDigits();

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $this->assertSame($constraint->message, $violation->getMessageTemplate());
        }
    }
}

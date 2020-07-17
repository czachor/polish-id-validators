<?php

declare(strict_types=1);

namespace Czachor\Tests\PolishIdValidators;


use Czachor\PolishIdValidators\Entities\PwzEntity;
use Czachor\PolishIdValidators\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;

final class PwzEntityTest extends TestCase
{
    public function testPwzEntityStartingWithZero(): void
    {
        $pwz_no = new PwzEntity('0123456');
        $violations = Validator::validate($pwz_no, 'pl_PL'); // example with specified language

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getCode() === 'PWZ_STARTS_WITH_ZERO') {
                    $this->assertSame('PWZ_STARTS_WITH_ZERO', $violation->getCode());

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzEmpty(): void
    {
        $pwz_no = new PwzEntity('');
        $violations = Validator::validate($pwz_no);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzEntity::ID_LENGTH . ' characters.') {
                    $this->assertSame(
                        'ID value should have exactly ' . PwzEntity::ID_LENGTH . ' characters.',
                        $violation->getMessage()
                    );

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzTooShort(): void
    {
        $pwz_no = new PwzEntity('1234');
        $violations = Validator::validate($pwz_no);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzEntity::ID_LENGTH . ' characters.') {
                    $this->assertTrue(true);

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzTooLong(): void
    {
        $pwz_no = new PwzEntity('123456789');
        $violations = Validator::validate($pwz_no);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzEntity::ID_LENGTH . ' characters.') {
                    $this->assertTrue(true);

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzInt(): void
    {
        $pwz_no = new PwzEntity(1234567);
        $violations = Validator::validate($pwz_no);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->assertSame('This value should be of type string.', $violation->getMessage());
            }
        }
    }

    public function testValidPwzId(): void
    {
        $pwz_no = new PwzEntity('5425740');
        $violations = Validator::validate($pwz_no);

        $this->assertSame(0, $violations->count());
    }

    public function testInValidCheckDigitPwzId(): void
    {
        $pwz_no = new PwzEntity('4425740');
        $violations = Validator::validate($pwz_no);

        $this->assertSame(1, $violations->count());

        foreach ($violations as $violation) {
            $this->assertSame('INVALID_CHECK_DIGIT', $violation->getCode());
        }
    }
}

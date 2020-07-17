<?php

declare(strict_types=1);

namespace Czachor\Tests\PolishIdValidators;


use Czachor\PolishIdValidators\Entities\PwzfEntity;
use Czachor\PolishIdValidators\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;

final class PwzfEntityTest extends TestCase
{
    public function testPwzfTooShortOrEmpty(): void
    {
        $the_id = new PwzfEntity('1234');
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzfEntity::ID_LENGTH . ' characters.') {
                    $this->assertTrue(true);

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzfTooLong(): void
    {
        $the_id = new PwzfEntity('1234567890');
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                if ($violation->getMessage() === 'ID value should have exactly ' . PwzfEntity::ID_LENGTH . ' characters.') {
                    $this->assertTrue(true);

                    return;
                }
            }
        }

        $this->fail('Missing error!');
    }

    public function testPwzfInt(): void
    {
        $the_id = new PwzfEntity(12345678);
        $violations = Validator::validate($the_id);

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->assertSame('This value should be of type string.', $violation->getMessage());
            }
        }
    }

    public function testValidPwzfId(): void
    {
        $ids = ['09014954', '11909125'];

        foreach ($ids as $id) {
            $the_id = new PwzfEntity($id);
            $violations = Validator::validate($the_id);

            $this->assertSame(0, $violations->count());
        }
    }

    public function testOutOfRangeChamberPwzfId(): void
    {
        // first two digits must be in range 00-22
        $ids = ['00909129', '98909131'];

        foreach ($ids as $id) {
            $the_id = new PwzfEntity($id);
            $violations = Validator::validate($the_id);

            $this->assertSame(1, $violations->count());
        }
    }
}

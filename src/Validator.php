<?php


namespace Czachor\PolishIdValidators;


use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    private function __construct(string $lang = 'en_US', ?string $own_t10n_resource_php_file = null)
    {
        $translator = new Translator($lang);
        $translator->setFallbackLocales(['en']);
        $translator->addLoader('php', new PhpFileLoader());
        $translator->addResource(
            'php',
            __DIR__ . '/translations/validators.en.php',
            'en'
        );

        $resource_file = null;

        if (!empty($own_t10n_resource_php_file)) {
            $resource_file = $own_t10n_resource_php_file;
        } elseif ($lang !== 'en_US') {
            $resource_file = __DIR__ . '/translations/validators.' . $lang . '.php';

            if (!file_exists($resource_file)) {
                $resource_file = null;
            }
        }

        if (!empty($resource_file)) {
            $translator->addResource(
                'php',
                $resource_file,
                $lang
            );
        }

        $this->validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->setTranslator($translator)
            ->getValidator();
    }

    private function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    public static function validate(EntityInterface $entity, string $lang = 'en_US', ?string $own_t10n_resource_php_file = null): ConstraintViolationListInterface
    {
        $obj_validator = new self($lang, $own_t10n_resource_php_file);

        return $obj_validator->getValidator()->validate($entity);
    }
}

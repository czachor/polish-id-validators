# Polish ID Validators
Version: 1.0

PHP library based on [Symfony Validator Component](https://symfony.com/doc/master/components/validator.html) to validate various medical identification numbers used in Poland.

Only algorithmical validation mostly based on a check digit.
It does not query official medical registries to fetch
any data or if a record (ID) exists at all.

## Available validators

* _Numer Prawa Wykonywania Zawodu Lekarza (PWZ)_ (Polish medical licence ID for physicians)

   ```php
   <?php
   use Czachor\PolishIdValidators\Entities\PwzEntity;
  
   $id = new PwzEntity('5425740');
   ```
  
   Example: `5425740`, total 7 digits:
   * check digit
   * 6 digits
   
   Source:
   * https://rejestr.nil.org.pl/
   * https://nil.org.pl/rejestry/centralny-rejestr-lekarzy/zasady-weryfikowania-nr-prawa-wykonywania-zawodu

* _Numer Prawa Wykonywania Zawodu Farmaceuty (PWZF)_ (pharmacists ID)
   ```php
   <?php
   use Czachor\PolishIdValidators\Entities\PwzfEntity;
  
   $id = new PwzfEntity('09014954');
   ```
  
   EAN-8 code, example: `09014954`, total 8 digits:
   * 2-digits local district chamber (`01` to `22`)
   * 5-digits sequential number
   * check digit
   
   Source:
   * https://crf.rejestrymedyczne.csioz.gov.pl/
   * https://www.nia.org.pl/dat/magazyn/biuletyn_IV_07_2005.pdf

* _Numer Prawa Wykonywania Zawodu Pielęgniarki/Położnej (PWZP)_ (nurses and midwives ID)
   ```php
   <?php
   use Czachor\PolishIdValidators\Entities\PwzpEntity;
  
   $id = new PwzpEntity('0201234P');
   ```
  
   Example: `0201234P`, total 8 chars: 7 digits and letter: 
   * 2-digits ID of local District Chamber of Nurses and Midwives (`01` to `45`)
   * 5-digits sequential number
   * letter `P` or `A`

    Source:
    * https://nipip.pl/weryfikacja-pwz/
    * https://nipip.pl/uchwala-nr-320-vii-2018-nrpip-z-dnia-12-wrzesnia-2018-r-w-sprawie-trybu-postepowania-dotyczacego-stwierdzania-i-przyznawania-prawa-wykonywania-zawodu-pielegniarki-i-zawodu-poloznej-oraz-sposobu-prow/

* _Numer Prawa Wykonywania Zawodu Diagnosty Laboratoryjnego (PWZDL)_ (laboratory diagnostician ID)

   ```php
   <?php
   use Czachor\PolishIdValidators\Entities\PwzdlEntity;
  
   $id = new PwzdlEntity('3143');
   ```
  
   Example: `3143`, PWZDL ID are simple sequential numbers, so there is very simple "is numeric" validation.

## Requirements

PHP 7.2+.

## Install

Via Composer

```bash
$ composer require czachor/polish-id-validators
```

## Usage

All ID must be passed as strings.

#### Direct method 
```php
<?php
use Czachor\PolishIdValidators\Entities\PwzEntity;
use Czachor\PolishIdValidators\Validator;

$pwz_id = new PwzEntity('5425740'); // Polish medical licence ID
/** @var Symfony\Component\Validator\ConstraintViolationListInterface $obj_validator */
$violations = Validator::validate($pwz_id);

if ($violations->count() > 0) {
    foreach ($violations as $violation) {
        echo 'Error! ' . $violation->getMessage();
    }
} else {
    echo 'Valid!';
}
```

#### With Symfony Component:

```php
<?php
use Czachor\PolishIdValidators\Entities\PwzEntity;
use Symfony\Component\Validator\Validation;

$pwz_id = new PwzEntity('5425740'); // Polish medical licence ID 
$validator = Validation::createValidatorBuilder()
    ->addMethodMapping('loadValidatorMetadata')
    ->getValidator();
$violations = $validator->validate($pwz_id);

// ...
```

## Translation
Translation uses [Symfony Translation Component](https://symfony.com/doc/4.2/components/translation.html).

Available languages:
* English (default) - `en_US`
* Polish - `pl_PL`

How to use:
```php
<php
$violations = Validator::validate($pwz_id, 'pl_PL');
```

If your language is unsupported, you can add it manually:

```php
<?php
$violations = Validator::validate($pwz_id, 'pt_BR', $path_to_your_php_resource_file);
``` 

Or if you want to use a different [loader](https://symfony.com/doc/master/components/translation.html#loading-message-catalogs):
```php
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Validator\Validation;
use Czachor\PolishIdValidators\Entities\PwzEntity;

$translator = new Translator('fr_FR');
$translator->addLoader('array', new ArrayLoader());
$translator->addResource('array', [
    'Hello World!' => 'Bonjour',
], 'fr_FR');

$validator = Validation::createValidatorBuilder()
    ->addMethodMapping('loadValidatorMetadata')
    ->setTranslator($translator)
    ->getValidator();

$pwz_id = new PwzEntity('5425740'); // Polish medical licence ID 
$violations = $validator->validate($pwz_id);
// ...
```


## Todo
* More validators
* Annotations
* More translations

## License
The MIT License (MIT).

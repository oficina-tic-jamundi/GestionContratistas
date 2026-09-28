<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Helpers\ColombianNit.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Helpers\ColombianNit
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-21d9db9579357009347e95b3ed96c822c45b01146968c22e1d9d502ecada9641',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Helpers\\ColombianNit',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Helpers/ColombianNit.php',
      ),
    ),
    'namespace' => 'Sigcon\\Helpers',
    'name' => 'Sigcon\\Helpers\\ColombianNit',
    'shortName' => 'ColombianNit',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Dígito de verificación del NIT según el algoritmo de la DIAN (módulo 11 con pesos primos).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 29,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'WEIGHTS' => 
      array (
        'declaringClassName' => 'Sigcon\\Helpers\\ColombianNit',
        'implementingClassName' => 'Sigcon\\Helpers\\ColombianNit',
        'name' => 'WEIGHTS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71]',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 33,
            'startFilePos' => 221,
            'endTokenPos' => 77,
            'endFilePos' => 278,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 87,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'verificationDigit' => 
      array (
        'name' => 'verificationDigit',
        'parameters' => 
        array (
          'nit' => 
          array (
            'name' => 'nit',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 14,
            'endLine' => 14,
            'startColumn' => 46,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 14,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Helpers',
        'declaringClassName' => 'Sigcon\\Helpers\\ColombianNit',
        'implementingClassName' => 'Sigcon\\Helpers\\ColombianNit',
        'currentClassName' => 'Sigcon\\Helpers\\ColombianNit',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));
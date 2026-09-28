<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Logging\SensitiveDataRedactor.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Logging\SensitiveDataRedactor
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-dd9088fbe30c6237d24c12d263fcaa8b2fbc9b04a1e911a6b2c96832e8a74e4f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Logging/SensitiveDataRedactor.php',
      ),
    ),
    'namespace' => 'Sigcon\\Logging',
    'name' => 'Sigcon\\Logging\\SensitiveDataRedactor',
    'shortName' => 'SensitiveDataRedactor',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Reemplaza por "[REDACTED]" cualquier valor del contexto cuya clave sugiera un secreto.
 * Es una red de seguridad: el código no debe enviar secretos al log en primer lugar.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 75,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'MASK' => 
      array (
        'declaringClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'implementingClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'name' => 'MASK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'[REDACTED]\'',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 47,
            'startFilePos' => 401,
            'endTokenPos' => 47,
            'endFilePos' => 412,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'SENSITIVE_KEY_FRAGMENTS' => 
      array (
        'declaringClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'implementingClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'name' => 'SENSITIVE_KEY_FRAGMENTS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'password\', \'passwd\', \'contrasena\', \'secret\', \'token\', \'authorization\', \'cookie\', \'private_key\', \'api_key\', \'apikey\', \'credential\', \'session_id\', \'csrf\']',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 33,
            'startTokenPos' => 60,
            'startFilePos' => 537,
            'endTokenPos' => 101,
            'endFilePos' => 801,
          ),
        ),
        'docComment' => '/** Fragmentos de clave (en minúsculas) que se consideran sensibles. */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      '__invoke' => 
      array (
        'name' => '__invoke',
        'parameters' => 
        array (
          'record' => 
          array (
            'name' => 'record',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Monolog\\LogRecord',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 30,
            'endColumn' => 46,
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
            'name' => 'Monolog\\LogRecord',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 35,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Logging',
        'declaringClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'implementingClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'currentClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'aliasName' => NULL,
      ),
      'redact' => 
      array (
        'name' => 'redact',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 28,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'depth' => 
          array (
            'name' => 'depth',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 47,
                'endLine' => 47,
                'startTokenPos' => 177,
                'startFilePos' => 1152,
                'endTokenPos' => 177,
                'endFilePos' => 1152,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array<mixed> $data
 * @return array<mixed>
 */',
        'startLine' => 47,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Logging',
        'declaringClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'implementingClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'currentClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'aliasName' => NULL,
      ),
      'isSensitiveKey' => 
      array (
        'name' => 'isSensitiveKey',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
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
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 37,
            'endColumn' => 47,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 64,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Sigcon\\Logging',
        'declaringClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'implementingClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
        'currentClassName' => 'Sigcon\\Logging\\SensitiveDataRedactor',
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
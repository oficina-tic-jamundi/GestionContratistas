<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Security\PasswordPolicy.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Security\PasswordPolicy
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-111e292c44daa77114325a005fc9300cb6abff1992b184ac22a6f44a81fab870',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Security\\PasswordPolicy',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Security/PasswordPolicy.php',
      ),
    ),
    'namespace' => 'Sigcon\\Security',
    'name' => 'Sigcon\\Security\\PasswordPolicy',
    'shortName' => 'PasswordPolicy',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Política de contraseñas, alineada con NIST SP 800-63B: se prioriza la longitud y se
 * rechazan contraseñas comunes o derivadas del correo, en lugar de exigir combinaciones
 * arbitrarias de símbolos.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 72,
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
      'MIN_LENGTH' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'implementingClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'name' => 'MIN_LENGTH',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '12',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 33,
            'startFilePos' => 336,
            'endTokenPos' => 33,
            'endFilePos' => 337,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'MAX_LENGTH' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'implementingClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'name' => 'MAX_LENGTH',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '128',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 44,
            'startFilePos' => 370,
            'endTokenPos' => 44,
            'endFilePos' => 372,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'COMMON' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'implementingClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'name' => 'COMMON',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'123456789012\', \'1234567890123\', \'qwertyuiopas\', \'contrasena123\', \'contraseña123\', \'password1234\', \'password12345\', \'passwordpassword\', \'administrador\', \'administrador1\', \'alcaldiajamundi\', \'jamundi123456\', \'jamundi2026\', \'sigcon123456\', \'sigcon2026\', \'colombia12345\', \'colombia2026\', \'bienvenido123\', \'bienvenido2026\', \'cambiar123456\', \'temporal123456\', \'aaaaaaaaaaaa\', \'111111111111\', \'000000000000\', \'abcdefghijkl\', \'iloveyou1234\', \'qwerty123456\', \'abc123456789\', \'contrasenasegura\', \'passw0rd1234\']',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 25,
            'startTokenPos' => 57,
            'startFilePos' => 505,
            'endTokenPos' => 149,
            'endFilePos' => 1063,
          ),
        ),
        'docComment' => '/** Contraseñas y patrones frecuentes (en minúsculas). Lista corta, sin dependencia externa. */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'violations' => 
      array (
        'name' => 'violations',
        'parameters' => 
        array (
          'password' => 
          array (
            'name' => 'password',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 32,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'email' => 
          array (
            'name' => 'email',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 30,
                'endLine' => 30,
                'startTokenPos' => 176,
                'startFilePos' => 1268,
                'endTokenPos' => 176,
                'endFilePos' => 1271,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 73,
            'endColumn' => 93,
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
 * @return list<string> Mensajes de incumplimiento (vacío si la contraseña es aceptable).
 */',
        'startLine' => 30,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'implementingClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'currentClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'aliasName' => NULL,
      ),
      'generateTemporary' => 
      array (
        'name' => 'generateTemporary',
        'parameters' => 
        array (
          'length' => 
          array (
            'name' => 'length',
            'default' => 
            array (
              'code' => '16',
              'attributes' => 
              array (
                'startLine' => 61,
                'endLine' => 61,
                'startTokenPos' => 412,
                'startFilePos' => 2518,
                'endTokenPos' => 412,
                'endFilePos' => 2519,
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
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 39,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Contraseña temporal aleatoria (asignada por un administrador). Evita caracteres
 * ambiguos (0/O, 1/l/I) para que pueda dictarse o transcribirse sin errores.
 */',
        'startLine' => 61,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'implementingClassName' => 'Sigcon\\Security\\PasswordPolicy',
        'currentClassName' => 'Sigcon\\Security\\PasswordPolicy',
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
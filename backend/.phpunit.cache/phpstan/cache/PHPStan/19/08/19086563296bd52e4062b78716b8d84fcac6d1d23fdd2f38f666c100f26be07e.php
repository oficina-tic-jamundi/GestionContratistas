<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Security\PasswordHasher.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Security\PasswordHasher
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-410dc4d3b6b8ce837b2e6e5eabf942f2438f54bcc278523b208234de0250cad2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Security\\PasswordHasher',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Security/PasswordHasher.php',
      ),
    ),
    'namespace' => 'Sigcon\\Security',
    'name' => 'Sigcon\\Security\\PasswordHasher',
    'shortName' => 'PasswordHasher',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Hash de contraseñas: Argon2id si el PHP del servidor lo soporta, bcrypt (costo 12) si no.
 *
 * Parámetros Argon2id: mínimo recomendado por OWASP (m=19 MiB, t=2, p=1). Los valores por
 * defecto de PHP (64 MiB, t=4) tardan ~0,5 s por intento: en hosting compartido eso agota
 * memoria/CPU y facilita la denegación de servicio con intentos de login masivos.
 * Si se cambian, los hashes existentes se actualizan solos al iniciar sesión (needsRehash).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 62,
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
      'DUMMY_ARGON2ID' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'name' => 'DUMMY_ARGON2ID',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'$argon2id$v=19$m=19456,t=2,p=1$dTl0TWxZWTZaVk9hVnBuaw$BKYvLvPBDPpuTSwueVAF7y/FfS+ADZXEULYTgOo+sh4\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 35,
            'startFilePos' => 795,
            'endTokenPos' => 35,
            'endFilePos' => 893,
          ),
        ),
        'docComment' => '/**
 * Hashes de una contraseña aleatoria descartada. Se verifican cuando el usuario no existe
 * para que el tiempo de respuesta no revele si una cuenta existe (anti-enumeración).
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 135,
      ),
      'DUMMY_BCRYPT' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'name' => 'DUMMY_BCRYPT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'$2y$12$kwWKoVQ9Ai/1WJo9qHOtx.Y5aYSlCmvDFU5CsLr4Deq8AtT.dw.t2\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 46,
            'startFilePos' => 929,
            'endTokenPos' => 46,
            'endFilePos' => 990,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 96,
      ),
    ),
    'immediateProperties' => 
    array (
      'algorithm' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'name' => 'algorithm',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'options' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'name' => 'options',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/** @var array<string, int> */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 29,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'currentClassName' => 'Sigcon\\Security\\PasswordHasher',
        'aliasName' => NULL,
      ),
      'hash' => 
      array (
        'name' => 'hash',
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
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 26,
            'endColumn' => 64,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'currentClassName' => 'Sigcon\\Security\\PasswordHasher',
        'aliasName' => NULL,
      ),
      'verify' => 
      array (
        'name' => 'verify',
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
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 28,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'hash' => 
          array (
            'name' => 'hash',
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
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 69,
            'endColumn' => 80,
            'parameterIndex' => 1,
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
        'startLine' => 45,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'currentClassName' => 'Sigcon\\Security\\PasswordHasher',
        'aliasName' => NULL,
      ),
      'needsRehash' => 
      array (
        'name' => 'needsRehash',
        'parameters' => 
        array (
          'hash' => 
          array (
            'name' => 'hash',
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
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 33,
            'endColumn' => 44,
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
        'startLine' => 50,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'currentClassName' => 'Sigcon\\Security\\PasswordHasher',
        'aliasName' => NULL,
      ),
      'verifyAgainstDummy' => 
      array (
        'name' => 'verifyAgainstDummy',
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 40,
            'endColumn' => 78,
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
        'docComment' => '/** Consume un tiempo equivalente a una verificación real. Siempre devuelve false. */',
        'startLine' => 56,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\PasswordHasher',
        'implementingClassName' => 'Sigcon\\Security\\PasswordHasher',
        'currentClassName' => 'Sigcon\\Security\\PasswordHasher',
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
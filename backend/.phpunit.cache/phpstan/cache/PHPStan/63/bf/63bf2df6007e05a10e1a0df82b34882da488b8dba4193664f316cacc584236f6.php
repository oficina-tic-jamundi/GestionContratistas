<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\System\SystemCheck.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\System\SystemCheck
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-1de924c8f566f0ef018f9d47f67725e8f37ea3ac0090514e44061e52dbb927c7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\System\\SystemCheck',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/System/SystemCheck.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\System',
    'name' => 'Sigcon\\Services\\System\\SystemCheck',
    'shortName' => 'SystemCheck',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Verificación del entorno para un despliegue (`php bin/console system:check`).
 * Cada comprobación es "ok", "warning" (funciona, pero conviene corregir) o "error"
 * (algo no va a funcionar). No muestra secretos: solo si están presentes y son válidos.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 180,
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
      'OK' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'OK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ok\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 58,
            'startFilePos' => 560,
            'endTokenPos' => 58,
            'endFilePos' => 563,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'WARNING' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'WARNING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'warning\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 69,
            'startFilePos' => 593,
            'endTokenPos' => 69,
            'endFilePos' => 601,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'ERROR' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'ERROR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'error\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 80,
            'startFilePos' => 629,
            'endTokenPos' => 80,
            'endFilePos' => 635,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'REQUIRED_EXTENSIONS' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'REQUIRED_EXTENSIONS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'pdo_mysql\', \'mbstring\', \'openssl\', \'fileinfo\', \'gd\', \'dom\', \'json\']',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 93,
            'startFilePos' => 737,
            'endTokenPos' => 113,
            'endFilePos' => 805,
          ),
        ),
        'docComment' => '/** Extensiones sin las cuales SIGCON no funciona. */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 110,
      ),
      'RECOMMENDED_EXTENSIONS' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'RECOMMENDED_EXTENSIONS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'exif\' => \'corrige la orientación de las fotos de evidencia\', \'zip\' => \'valida con precisión los documentos DOCX/XLSX\', \'intl\' => \'formatos regionales\', \'sodium\' => \'criptografía moderna\']',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 32,
            'startTokenPos' => 126,
            'startFilePos' => 929,
            'endTokenPos' => 156,
            'endFilePos' => 1159,
          ),
        ),
        'docComment' => '/** Recomendadas: sin ellas algo se degrada, pero el sistema funciona. */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'settings' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'settings',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Config\\Settings',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 9,
        'endColumn' => 43,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'database' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'database',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Database\\Database',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 9,
        'endColumn' => 43,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'migrator' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'migrator',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Database\\Migrator',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 9,
        'endColumn' => 43,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'jobs' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'name' => 'jobs',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Services\\Jobs\\JobAdminService',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 9,
        'endColumn' => 46,
        'isPromoted' => true,
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
          'settings' => 
          array (
            'name' => 'settings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Config\\Settings',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 9,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'database' => 
          array (
            'name' => 'database',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Database\\Database',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 9,
            'endColumn' => 43,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'migrator' => 
          array (
            'name' => 'migrator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Database\\Migrator',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 9,
            'endColumn' => 43,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'jobs' => 
          array (
            'name' => 'jobs',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Services\\Jobs\\JobAdminService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 9,
            'endColumn' => 46,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\System',
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'currentClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'aliasName' => NULL,
      ),
      'run' => 
      array (
        'name' => 'run',
        'parameters' => 
        array (
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
        'docComment' => '/** @return list<array{check: string, status: string, detail: string}> */',
        'startLine' => 43,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\System',
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'currentClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'aliasName' => NULL,
      ),
      'writable' => 
      array (
        'name' => 'writable',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 38,
            'endColumn' => 49,
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
        'startLine' => 149,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\System',
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'currentClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'aliasName' => NULL,
      ),
      'bytes' => 
      array (
        'name' => 'bytes',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 35,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 165,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\System',
        'declaringClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'implementingClassName' => 'Sigcon\\Services\\System\\SystemCheck',
        'currentClassName' => 'Sigcon\\Services\\System\\SystemCheck',
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
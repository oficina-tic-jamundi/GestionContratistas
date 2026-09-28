<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\Reports\Compose\ComposedDocumentRenderer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\Reports\Compose\ComposedDocumentRenderer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-98f34fe73fe7f350f2a13c2d8c299232527de02af8c7eb643239a9bdfc40919a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/Reports/Compose/ComposedDocumentRenderer.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\Reports\\Compose',
    'name' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
    'shortName' => 'ComposedDocumentRenderer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * PDF del acta o del informe creado desde la actividad (ADR-023).
 *
 * Mismas garantías que el PDF de versiones (ADR-017): dompdf sin acceso remoto, sin PHP ni
 * JavaScript, todo el texto escapado y las fotos incrustadas como data URI reducidas.
 * El diseño es un formato PROVISIONAL de SIGCON hasta que la Alcaldía entregue el oficial.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 182,
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
      'PHOTO_MAX_SIDE' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'implementingClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'name' => 'PHOTO_MAX_SIDE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1200',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 60,
            'startFilePos' => 711,
            'endTokenPos' => 60,
            'endFilePos' => 714,
          ),
        ),
        'docComment' => '/** Lado mayor de las fotos dentro del PDF: suficiente para leerlas, sin inflar el archivo. */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
    ),
    'immediateProperties' => 
    array (
      'settings' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'implementingClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
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
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 33,
        'endColumn' => 67,
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
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 33,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 25,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\Reports\\Compose',
        'declaringClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'implementingClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'currentClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
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
                'name' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentData',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 28,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'context' => 
          array (
            'name' => 'context',
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
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 56,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'photos' => 
          array (
            'name' => 'photos',
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
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 72,
            'endColumn' => 84,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'generatedAt' => 
          array (
            'name' => 'generatedAt',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateTimeImmutable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 87,
            'endColumn' => 116,
            'parameterIndex' => 3,
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
        'docComment' => '/**
 * @param array{contract_number: string, contractor: string, supervisor: ?string, department: string, activity: string, obligation: string, period: string} $context
 * @param list<array{bytes: string, caption: string}> $photos
 */',
        'startLine' => 33,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\Reports\\Compose',
        'declaringClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'implementingClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'currentClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'aliasName' => NULL,
      ),
      'html' => 
      array (
        'name' => 'html',
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
                'name' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentData',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 26,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'context' => 
          array (
            'name' => 'context',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 54,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'photos' => 
          array (
            'name' => 'photos',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 70,
            'endColumn' => 82,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'generatedAt' => 
          array (
            'name' => 'generatedAt',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateTimeImmutable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 85,
            'endColumn' => 114,
            'parameterIndex' => 3,
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
        'docComment' => '/**
 * @param array{contract_number: string, contractor: string, supervisor: ?string, department: string, activity: string, obligation: string, period: string} $context
 * @param list<array{bytes: string, caption: string}> $photos
 */',
        'startLine' => 63,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\Reports\\Compose',
        'declaringClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'implementingClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'currentClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'aliasName' => NULL,
      ),
      'shrink' => 
      array (
        'name' => 'shrink',
        'parameters' => 
        array (
          'bytes' => 
          array (
            'name' => 'bytes',
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
            'startLine' => 161,
            'endLine' => 161,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
        'attributes' => 
        array (
        ),
        'docComment' => '/** Reduce la foto a JPEG de lado máximo PHOTO_MAX_SIDE. Null si no se puede leer. */',
        'startLine' => 161,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Reports\\Compose',
        'declaringClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'implementingClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
        'currentClassName' => 'Sigcon\\Services\\Reports\\Compose\\ComposedDocumentRenderer',
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
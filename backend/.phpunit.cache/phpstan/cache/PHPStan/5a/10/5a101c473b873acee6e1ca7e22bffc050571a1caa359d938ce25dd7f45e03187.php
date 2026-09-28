<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Http\Resources\ContractResources.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Http\Resources\ContractResources
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-93a4eccaad49215b4c01638aa3836bc6e7684962b829d45bf93b0c9b654dd015',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Http\\Resources\\ContractResources',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Http/Resources/ContractResources.php',
      ),
    ),
    'namespace' => 'Sigcon\\Http\\Resources',
    'name' => 'Sigcon\\Http\\Resources\\ContractResources',
    'shortName' => 'ContractResources',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Representación pública de dependencias, contratistas, contratos e historial.
 * Nunca incluye ids internos.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 115,
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
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'department' => 
      array (
        'name' => 'department',
        'parameters' => 
        array (
          'd' => 
          array (
            'name' => 'd',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Models\\Department',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 39,
            'endColumn' => 51,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return array<string, mixed> */',
        'startLine' => 22,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Http\\Resources',
        'declaringClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'implementingClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'currentClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'aliasName' => NULL,
      ),
      'contractor' => 
      array (
        'name' => 'contractor',
        'parameters' => 
        array (
          'c' => 
          array (
            'name' => 'c',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Models\\Contractor',
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
            'startColumn' => 39,
            'endColumn' => 51,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return array<string, mixed> */',
        'startLine' => 35,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Http\\Resources',
        'declaringClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'implementingClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'currentClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'aliasName' => NULL,
      ),
      'contract' => 
      array (
        'name' => 'contract',
        'parameters' => 
        array (
          'c' => 
          array (
            'name' => 'c',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Models\\Contract',
                'isIdentifier' => false,
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
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'canManage' => 
          array (
            'name' => 'canManage',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 61,
                'endLine' => 61,
                'startTokenPos' => 398,
                'startFilePos' => 2002,
                'endTokenPos' => 398,
                'endFilePos' => 2006,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
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
            'startColumn' => 50,
            'endColumn' => 72,
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
 * @param bool $canManage si el usuario puede gestionar contratos (determina las acciones disponibles)
 * @return array<string, mixed>
 */',
        'startLine' => 61,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Http\\Resources',
        'declaringClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'implementingClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'currentClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'aliasName' => NULL,
      ),
      'history' => 
      array (
        'name' => 'history',
        'parameters' => 
        array (
          'h' => 
          array (
            'name' => 'h',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Models\\HistoryEntry',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 36,
            'endColumn' => 50,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return array<string, mixed> */',
        'startLine' => 95,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Http\\Resources',
        'declaringClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'implementingClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'currentClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'aliasName' => NULL,
      ),
      'supervisor' => 
      array (
        'name' => 'supervisor',
        'parameters' => 
        array (
          'u' => 
          array (
            'name' => 'u',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Models\\User',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 39,
            'endColumn' => 45,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return array{uuid: string, name: string, email: string, department: ?string} */',
        'startLine' => 111,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Http\\Resources',
        'declaringClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'implementingClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
        'currentClassName' => 'Sigcon\\Http\\Resources\\ContractResources',
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
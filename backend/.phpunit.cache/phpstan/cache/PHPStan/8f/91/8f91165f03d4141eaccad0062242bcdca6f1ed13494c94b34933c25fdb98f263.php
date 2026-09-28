<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\AI\DisabledAIProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\AI\DisabledAIProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-494a875d86b1ea451a35bf4430ec795d03e71f53fd230b589cbbc38092913475',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/AI/DisabledAIProvider.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\AI',
    'name' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
    'shortName' => 'DisabledAIProvider',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Proveedor por defecto: la IA NO está habilitada (ADR-008). Nunca simula resultados:
 * responde explícitamente que la funcionalidad no está disponible.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 22,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Sigcon\\Services\\AI\\AIProviderInterface',
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
      'isEnabled' => 
      array (
        'name' => 'isEnabled',
        'parameters' => 
        array (
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
        'startLine' => 13,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\AI',
        'declaringClassName' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
        'implementingClassName' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
        'currentClassName' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
        'aliasName' => NULL,
      ),
      'complete' => 
      array (
        'name' => 'complete',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Services\\AI\\AiRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 30,
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
            'name' => 'Sigcon\\Services\\AI\\AiResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 18,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\AI',
        'declaringClassName' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
        'implementingClassName' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
        'currentClassName' => 'Sigcon\\Services\\AI\\DisabledAIProvider',
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
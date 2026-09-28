<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\AI\AIProviderInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\AI\AIProviderInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-b30a873dc60f41a13ac1a49aeb932b3ef2375d73ac61a5efa6eb2c7d7c50bc62',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\AI\\AIProviderInterface',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/AI/AIProviderInterface.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\AI',
    'name' => 'Sigcon\\Services\\AI\\AIProviderInterface',
    'shortName' => 'AIProviderInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Proveedor de inteligencia artificial intercambiable (ADR-008).
 *
 * Hoy la única implementación es DisabledAIProvider. Habilitar un proveedor real exige un ADR
 * institucional (proveedor, base legal y tratamiento de datos personales según la Ley 1581 de
 * 2012, minimización, retención y quién puede usarlo) y registrar cada consulta en la auditoría.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 22,
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
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\AI',
        'declaringClassName' => 'Sigcon\\Services\\AI\\AIProviderInterface',
        'implementingClassName' => 'Sigcon\\Services\\AI\\AIProviderInterface',
        'currentClassName' => 'Sigcon\\Services\\AI\\AIProviderInterface',
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
            'startLine' => 21,
            'endLine' => 21,
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
        'docComment' => '/**
 * @throws FeatureDisabledException si el proveedor no está habilitado
 */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 61,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\AI',
        'declaringClassName' => 'Sigcon\\Services\\AI\\AIProviderInterface',
        'implementingClassName' => 'Sigcon\\Services\\AI\\AIProviderInterface',
        'currentClassName' => 'Sigcon\\Services\\AI\\AIProviderInterface',
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
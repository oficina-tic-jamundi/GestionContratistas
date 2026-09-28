<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\Activities\ProgressCalculator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\Activities\ProgressCalculator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-6f6438fed34b0b3ad281ae191fbfcf763cbcff5ef874e92fcd361b998e602f16',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/Activities/ProgressCalculator.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\Activities',
    'name' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
    'shortName' => 'ProgressCalculator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Promedio ponderado del avance (ADR-013), con aritmética entera en centésimas:
 * sin errores de coma flotante y sin depender de la extensión bcmath.
 *
 *   avance_padre = Σ(avance_hijo × peso_hijo) / Σ(peso_hijo)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 50,
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
      'weightedAverage' => 
      array (
        'name' => 'weightedAverage',
        'parameters' => 
        array (
          'children' => 
          array (
            'name' => 'children',
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
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 44,
            'endColumn' => 58,
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
        'docComment' => '/**
 * @param list<array{progress: string, weight: string}> $children valores decimales como texto
 * @return string avance con 2 decimales ("0.00" si no hay hijos)
 */',
        'startLine' => 19,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'aliasName' => NULL,
      ),
      'toHundredths' => 
      array (
        'name' => 'toHundredths',
        'parameters' => 
        array (
          'decimal' => 
          array (
            'name' => 'decimal',
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
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 41,
            'endColumn' => 55,
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
        'startLine' => 37,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'aliasName' => NULL,
      ),
      'fromHundredths' => 
      array (
        'name' => 'fromHundredths',
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
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 43,
            'endColumn' => 52,
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
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ProgressCalculator',
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
<?php declare(strict_types = 1);

// osfsl-C:/alcaldia/GECO_INFO/backend/vendor/composer/../php-di/php-di/src/Definition/Source/DefinitionSource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-DI\Definition\Source\DefinitionSource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a84f4bbcc121373a040d6ac848abec1fc09ce631b3217d33881647039311ea87-8.3-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'DI\\Definition\\Source\\DefinitionSource',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/vendor/composer/../php-di/php-di/src/Definition/Source/DefinitionSource.php',
      ),
    ),
    'namespace' => 'DI\\Definition\\Source',
    'name' => 'DI\\Definition\\Source\\DefinitionSource',
    'shortName' => 'DefinitionSource',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Source of definitions for entries of the container.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 28,
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
      'getDefinition' => 
      array (
        'name' => 'getDefinition',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 35,
            'endColumn' => 46,
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
                  'name' => 'DI\\Definition\\Definition',
                  'isIdentifier' => false,
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
        'docComment' => '/**
 * Returns the DI definition for the entry name.
 *
 * @throws InvalidDefinition An invalid definition was found.
 */',
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'DI\\Definition\\Source',
        'declaringClassName' => 'DI\\Definition\\Source\\DefinitionSource',
        'implementingClassName' => 'DI\\Definition\\Source\\DefinitionSource',
        'currentClassName' => 'DI\\Definition\\Source\\DefinitionSource',
        'aliasName' => NULL,
      ),
      'getDefinitions' => 
      array (
        'name' => 'getDefinitions',
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
        'docComment' => '/**
 * @return array<string,Definition> Definitions indexed by their name.
 */',
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'DI\\Definition\\Source',
        'declaringClassName' => 'DI\\Definition\\Source\\DefinitionSource',
        'implementingClassName' => 'DI\\Definition\\Source\\DefinitionSource',
        'currentClassName' => 'DI\\Definition\\Source\\DefinitionSource',
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
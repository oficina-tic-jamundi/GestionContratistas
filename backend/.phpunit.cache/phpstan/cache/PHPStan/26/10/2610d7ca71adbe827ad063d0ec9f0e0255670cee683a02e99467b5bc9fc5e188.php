<?php declare(strict_types = 1);

// osfsl-C:/alcaldia/GECO_INFO/backend/vendor/composer/../slim/slim/Slim/Interfaces/RouteGroupInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Slim\Interfaces\RouteGroupInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8aa4e55f18fbb782ac695f5f70d68f1f8a53007a34709a1f8d856d7a12c6cae1-8.3-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Slim\\Interfaces\\RouteGroupInterface',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/vendor/composer/../slim/slim/Slim/Interfaces/RouteGroupInterface.php',
      ),
    ),
    'namespace' => 'Slim\\Interfaces',
    'name' => 'Slim\\Interfaces\\RouteGroupInterface',
    'shortName' => 'RouteGroupInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/** @api */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 43,
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
      'collectRoutes' => 
      array (
        'name' => 'collectRoutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Slim\\Interfaces\\RouteGroupInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'implementingClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'currentClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 25,
            'endColumn' => 35,
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
            'name' => 'Slim\\Interfaces\\RouteGroupInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add middleware to the route group
 *
 * @param MiddlewareInterface|string|callable $middleware
 */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'implementingClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'currentClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'aliasName' => NULL,
      ),
      'addMiddleware' => 
      array (
        'name' => 'addMiddleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Psr\\Http\\Server\\MiddlewareInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 35,
            'endColumn' => 65,
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
            'name' => 'Slim\\Interfaces\\RouteGroupInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add middleware to the route group
 */',
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 88,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'implementingClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'currentClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'aliasName' => NULL,
      ),
      'appendMiddlewareToDispatcher' => 
      array (
        'name' => 'appendMiddlewareToDispatcher',
        'parameters' => 
        array (
          'dispatcher' => 
          array (
            'name' => 'dispatcher',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Slim\\MiddlewareDispatcher',
                'isIdentifier' => false,
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
            'startColumn' => 50,
            'endColumn' => 81,
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
            'name' => 'Slim\\Interfaces\\RouteGroupInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Append the group\'s middleware to the MiddlewareDispatcher
 * @param MiddlewareDispatcher<\\Psr\\Container\\ContainerInterface|null> $dispatcher
 */',
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 104,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'implementingClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'currentClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'aliasName' => NULL,
      ),
      'getPattern' => 
      array (
        'name' => 'getPattern',
        'parameters' => 
        array (
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
 * Get the RouteGroup\'s pattern
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'implementingClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
        'currentClassName' => 'Slim\\Interfaces\\RouteGroupInterface',
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
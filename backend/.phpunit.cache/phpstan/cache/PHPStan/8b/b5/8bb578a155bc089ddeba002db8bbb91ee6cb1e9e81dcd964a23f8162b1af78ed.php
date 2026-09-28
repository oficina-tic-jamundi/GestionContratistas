<?php declare(strict_types = 1);

// osfsl-C:/alcaldia/GECO_INFO/backend/vendor/composer/../slim/slim/Slim/Interfaces/MiddlewareDispatcherInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Slim\Interfaces\MiddlewareDispatcherInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-92324a08131ad4cac6810da88fef496400e2f71820a6e15c1b1573b9850ff175-8.3-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/vendor/composer/../slim/slim/Slim/Interfaces/MiddlewareDispatcherInterface.php',
      ),
    ),
    'namespace' => 'Slim\\Interfaces',
    'name' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
    'shortName' => 'MiddlewareDispatcherInterface',
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
      0 => 'Psr\\Http\\Server\\RequestHandlerInterface',
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
            'startLine' => 28,
            'endLine' => 28,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a new middleware to the stack
 *
 * Middleware are organized as a stack. That means middleware
 * that have been added before will be executed after the newly
 * added one (last in, first out).
 *
 * @param MiddlewareInterface|string|callable $middleware
 */',
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'implementingClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'currentClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
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
            'startLine' => 37,
            'endLine' => 37,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a new middleware to the stack
 *
 * Middleware are organized as a stack. That means middleware
 * that have been added before will be executed after the newly
 * added one (last in, first out).
 */',
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 73,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'implementingClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'currentClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'aliasName' => NULL,
      ),
      'seedMiddlewareStack' => 
      array (
        'name' => 'seedMiddlewareStack',
        'parameters' => 
        array (
          'kernel' => 
          array (
            'name' => 'kernel',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Psr\\Http\\Server\\RequestHandlerInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 41,
            'endColumn' => 71,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Seed the middleware stack with the inner request handler
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 79,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Slim\\Interfaces',
        'declaringClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'implementingClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
        'currentClassName' => 'Slim\\Interfaces\\MiddlewareDispatcherInterface',
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
<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Middleware\RequestContextMiddleware.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Middleware\RequestContextMiddleware
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-ff7fc0fdc98f2860f5ee48c04b47b3c1d4ee9721b1d4682b62b0cea6d7f8992d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Middleware/RequestContextMiddleware.php',
      ),
    ),
    'namespace' => 'Sigcon\\Middleware',
    'name' => 'Sigcon\\Middleware\\RequestContextMiddleware',
    'shortName' => 'RequestContextMiddleware',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Inicia el contexto de la solicitud: identificador único, IP y user agent.
 *
 * El ID se devuelve en la cabecera X-Request-Id y en los errores, y se adjunta a logs y
 * auditoría, para que un usuario pueda reportar un problema con un código rastreable.
 *
 * La IP es REMOTE_ADDR. No se confía en X-Forwarded-For: en cPanel la conexión llega directa
 * a Apache. Si en el futuro se usa un proxy (ej. Cloudflare) se configurará explícitamente.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 46,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Psr\\Http\\Server\\MiddlewareInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ATTRIBUTE' => 
      array (
        'declaringClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'implementingClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'name' => 'ATTRIBUTE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'request_id\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 62,
            'startFilePos' => 830,
            'endTokenPos' => 62,
            'endFilePos' => 841,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'HEADER' => 
      array (
        'declaringClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'implementingClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'name' => 'HEADER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'X-Request-Id\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 73,
            'startFilePos' => 870,
            'endTokenPos' => 73,
            'endFilePos' => 883,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
    ),
    'immediateProperties' => 
    array (
      'context' => 
      array (
        'declaringClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'implementingClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'name' => 'context',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Logging\\RequestContext',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 33,
        'endColumn' => 72,
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
          'context' => 
          array (
            'name' => 'context',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Logging\\RequestContext',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 33,
            'endColumn' => 72,
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
        'startLine' => 27,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Middleware',
        'declaringClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'implementingClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'currentClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'aliasName' => NULL,
      ),
      'process' => 
      array (
        'name' => 'process',
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
                'name' => 'Psr\\Http\\Message\\ServerRequestInterface',
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
            'startColumn' => 29,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'handler' => 
          array (
            'name' => 'handler',
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
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 62,
            'endColumn' => 93,
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
            'name' => 'Psr\\Http\\Message\\ResponseInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 31,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Middleware',
        'declaringClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'implementingClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
        'currentClassName' => 'Sigcon\\Middleware\\RequestContextMiddleware',
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
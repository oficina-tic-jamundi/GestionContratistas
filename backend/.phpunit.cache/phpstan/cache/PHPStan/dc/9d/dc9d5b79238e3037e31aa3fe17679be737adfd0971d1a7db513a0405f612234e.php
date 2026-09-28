<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Bootstrap\Kernel.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Bootstrap\Kernel
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-8de309f63f28606cc3099c497ca2cf839fb33256e28c9adcc0c0cba3c430e6d6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Bootstrap\\Kernel',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Bootstrap/Kernel.php',
      ),
    ),
    'namespace' => 'Sigcon\\Bootstrap',
    'name' => 'Sigcon\\Bootstrap\\Kernel',
    'shortName' => 'Kernel',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Punto único de arranque, compartido por HTTP (public/index.php) y consola (bin/console).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 107,
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
      'envDir' => 
      array (
        'name' => 'envDir',
        'parameters' => 
        array (
          'rootPath' => 
          array (
            'name' => 'rootPath',
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
            'startColumn' => 35,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Carpeta del .env (recomendado en cPanel: fuera del directorio del código).
 *
 * - Web: la constante SIGCON_ENV_DIR que define el index.php público. Es local a la petición;
 *   NO se usa putenv()/getenv() porque el entorno del proceso no es seguro entre hilos (en un
 *   Apache con hilos, una petición podía leer el entorno mientras otra lo modificaba y
 *   cargar el .env equivocado).
 * - Consola, Cron y servidor de desarrollo (php -S): la variable de entorno SIGCON_ENV_DIR.
 *   Cada ejecución es un proceso propio y php -S atiende una petición a la vez, así que el
 *   entorno no se comparte entre hilos. php -S no copia el entorno a $_SERVER.
 */',
        'startLine' => 37,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Bootstrap',
        'declaringClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'implementingClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'currentClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'aliasName' => NULL,
      ),
      'container' => 
      array (
        'name' => 'container',
        'parameters' => 
        array (
          'rootPath' => 
          array (
            'name' => 'rootPath',
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 38,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'env' => 
          array (
            'name' => 'env',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 55,
                'endLine' => 55,
                'startTokenPos' => 245,
                'startFilePos' => 2266,
                'endTokenPos' => 245,
                'endFilePos' => 2269,
              ),
            ),
            'type' => 
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
                      'name' => 'array',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 56,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'overrides' => 
          array (
            'name' => 'overrides',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 55,
                'endLine' => 55,
                'startTokenPos' => 254,
                'startFilePos' => 2291,
                'endTokenPos' => 255,
                'endFilePos' => 2292,
              ),
            ),
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 76,
            'endColumn' => 96,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Container\\ContainerInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string $rootPath Directorio backend/.
 * @param array<string, mixed>|null $env Variables explícitas (pruebas). Si es null se leen de .env y del entorno.
 * @param array<string, mixed> $overrides Definiciones que reemplazan las de config/container.php (solo pruebas).
 */',
        'startLine' => 55,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Bootstrap',
        'declaringClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'implementingClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'currentClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'aliasName' => NULL,
      ),
      'http' => 
      array (
        'name' => 'http',
        'parameters' => 
        array (
          'container' => 
          array (
            'name' => 'container',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Psr\\Container\\ContainerInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 33,
            'endColumn' => 61,
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
            'name' => 'Slim\\App',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return App<ContainerInterface> */',
        'startLine' => 79,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Bootstrap',
        'declaringClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'implementingClassName' => 'Sigcon\\Bootstrap\\Kernel',
        'currentClassName' => 'Sigcon\\Bootstrap\\Kernel',
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
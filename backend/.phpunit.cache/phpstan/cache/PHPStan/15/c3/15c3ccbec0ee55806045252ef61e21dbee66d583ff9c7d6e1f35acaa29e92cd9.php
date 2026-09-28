<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Console\Commands\JobsRunCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Console\Commands\JobsRunCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-12913bcd4f8863bbb4300c0a8645ac396fa14a6aedae633d00dee34a13256957',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Console/Commands/JobsRunCommand.php',
      ),
    ),
    'namespace' => 'Sigcon\\Console\\Commands',
    'name' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
    'shortName' => 'JobsRunCommand',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Procesa la cola de trabajos. Lo ejecuta el Cron de cPanel cada 5 minutos (ADR-005, ADR-017):
 *
 *   php bin/console jobs:run            procesa hasta agotar la cola o el tiempo máximo
 *   php bin/console jobs:run --max=10   como máximo 10 trabajos
 *
 * Es seguro que dos ejecuciones se solapen: cada trabajo lo toma un único procesador.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 53,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Sigcon\\Console\\Command',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'runner' => 
      array (
        'declaringClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'implementingClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'name' => 'runner',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Services\\Jobs\\JobRunner',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 33,
        'endColumn' => 66,
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
          'runner' => 
          array (
            'name' => 'runner',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Services\\Jobs\\JobRunner',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 33,
            'endColumn' => 66,
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
        'startLine' => 21,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Console\\Commands',
        'declaringClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'implementingClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'currentClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'aliasName' => NULL,
      ),
      'name' => 
      array (
        'name' => 'name',
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
        'docComment' => NULL,
        'startLine' => 25,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Console\\Commands',
        'declaringClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'implementingClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'currentClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'aliasName' => NULL,
      ),
      'description' => 
      array (
        'name' => 'description',
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
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Console\\Commands',
        'declaringClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'implementingClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'currentClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'aliasName' => NULL,
      ),
      'run' => 
      array (
        'name' => 'run',
        'parameters' => 
        array (
          'arguments' => 
          array (
            'name' => 'arguments',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 25,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'output' => 
          array (
            'name' => 'output',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Console\\Output',
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
            'startColumn' => 43,
            'endColumn' => 56,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 35,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Console\\Commands',
        'declaringClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'implementingClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
        'currentClassName' => 'Sigcon\\Console\\Commands\\JobsRunCommand',
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
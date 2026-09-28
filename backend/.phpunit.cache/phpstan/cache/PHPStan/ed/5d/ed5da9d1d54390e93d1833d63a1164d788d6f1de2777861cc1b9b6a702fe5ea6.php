<?php declare(strict_types = 1);

// osfsl-C:/alcaldia/GECO_INFO/backend/vendor/composer/../monolog/monolog/src/Monolog/Level.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Monolog\Level
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a8c1b1235dae0025d18971b1b357727419aab3591e83139caa3d74eaf123928f-8.3-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Monolog\\Level',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/vendor/composer/../monolog/monolog/src/Monolog/Level.php',
      ),
    ),
    'namespace' => 'Monolog',
    'name' => 'Monolog\\Level',
    'shortName' => 'Level',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => true,
    'isBackedEnum' => true,
    'modifiers' => 0,
    'docComment' => '/**
 * Represents the log levels
 *
 * Monolog supports the logging levels described by RFC 5424 {@see https://datatracker.ietf.org/doc/html/rfc5424}
 * but due to BC the severity values used internally are not 0-7.
 *
 * To get the level name/value out of a Level there are several options:
 *
 * - Use ->getName() to get the standard Monolog name which is full uppercased (e.g. "DEBUG")
 * - Use ->toPsrLogLevel() to get the standard PSR-3 name which is full lowercased (e.g. "debug")
 * - Use ->toRFC5424Level() to get the standard RFC 5424 value (e.g. 7 for debug, 0 for emergency)
 * - Use ->name to get the enum case\'s name which is capitalized (e.g. "Debug")
 *
 * To get the internal value for filtering, if the includes/isLowerThan/isHigherThan methods are
 * not enough, you can use ->value to get the enum case\'s integer value.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 209,
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
      'VALUES' => 
      array (
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'name' => 'VALUES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[100, 200, 250, 300, 400, 500, 550, 600]',
          'attributes' => 
          array (
            'startLine' => 188,
            'endLine' => 197,
            'startTokenPos' => 687,
            'startFilePos' => 5037,
            'endTokenPos' => 713,
            'endFilePos' => 5147,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 188,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'NAMES' => 
      array (
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'name' => 'NAMES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'DEBUG\', \'INFO\', \'NOTICE\', \'WARNING\', \'ERROR\', \'CRITICAL\', \'ALERT\', \'EMERGENCY\']',
          'attributes' => 
          array (
            'startLine' => 199,
            'endLine' => 208,
            'startTokenPos' => 724,
            'startFilePos' => 5176,
            'endTokenPos' => 750,
            'endFilePos' => 5327,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 199,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'name' => 
      array (
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'name' => 'name',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'value' => 
      array (
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'name' => 'value',
        'modifiers' => 2177,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'fromName' => 
      array (
        'name' => 'fromName',
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
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 37,
            'endColumn' => 48,
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
 * @param  value-of<self::NAMES>|LogLevel::*|\'Debug\'|\'Info\'|\'Notice\'|\'Warning\'|\'Error\'|\'Critical\'|\'Alert\'|\'Emergency\' $name
 * @return static
 */',
        'startLine' => 88,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'fromValue' => 
      array (
        'name' => 'fromValue',
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
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 38,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  value-of<self::VALUES> $value
 * @return static
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'includes' => 
      array (
        'name' => 'includes',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Monolog\\Level',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 30,
            'endColumn' => 41,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns true if the passed $level is higher or equal to $this
 */',
        'startLine' => 114,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'isHigherThan' => 
      array (
        'name' => 'isHigherThan',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Monolog\\Level',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 34,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'isLowerThan' => 
      array (
        'name' => 'isLowerThan',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Monolog\\Level',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 33,
            'endColumn' => 44,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 124,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'getName' => 
      array (
        'name' => 'getName',
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
 * Returns the monolog standardized all-capitals name of the level
 *
 * Use this instead of $level->name which returns the enum case name (e.g. Debug vs DEBUG if you use getName())
 *
 * @return value-of<self::NAMES>
 */',
        'startLine' => 136,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'toPsrLogLevel' => 
      array (
        'name' => 'toPsrLogLevel',
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
 * Returns the PSR-3 level matching this instance
 *
 * @phpstan-return \\Psr\\Log\\LogLevel::*
 */',
        'startLine' => 155,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'toRFC5424Level' => 
      array (
        'name' => 'toRFC5424Level',
        'parameters' => 
        array (
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
        'docComment' => '/**
 * Returns the RFC 5424 level matching this instance
 *
 * @phpstan-return int<0, 7>
 */',
        'startLine' => 174,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'cases' => 
      array (
        'name' => 'cases',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
            'name' => 'static',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
        'aliasName' => NULL,
      ),
      'tryFrom' => 
      array (
        'name' => 'tryFrom',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
                  'name' => 'static',
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
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Monolog',
        'declaringClassName' => 'Monolog\\Level',
        'implementingClassName' => 'Monolog\\Level',
        'currentClassName' => 'Monolog\\Level',
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
    'backingType' => 
    array (
      'name' => 'int',
      'isIdentifier' => true,
    ),
    'cases' => 
    array (
      'Debug' => 
      array (
        'name' => 'Debug',
        'value' => 
        array (
          'code' => '100',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 40,
            'startFilePos' => 1232,
            'endTokenPos' => 40,
            'endFilePos' => 1234,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Detailed debug information
 */',
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Info' => 
      array (
        'name' => 'Info',
        'value' => 
        array (
          'code' => '200',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 51,
            'startFilePos' => 1344,
            'endTokenPos' => 51,
            'endFilePos' => 1346,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Interesting events
 *
 * Examples: User logs in, SQL logs.
 */',
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 20,
      ),
      'Notice' => 
      array (
        'name' => 'Notice',
        'value' => 
        array (
          'code' => '250',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 62,
            'startFilePos' => 1407,
            'endTokenPos' => 62,
            'endFilePos' => 1409,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Uncommon events
 */',
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 22,
      ),
      'Warning' => 
      array (
        'name' => 'Warning',
        'value' => 
        array (
          'code' => '300',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 73,
            'startFilePos' => 1625,
            'endTokenPos' => 73,
            'endFilePos' => 1627,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Exceptional occurrences that are not errors
 *
 * Examples: Use of deprecated APIs, poor use of an API,
 * undesirable things that are not necessarily wrong.
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 23,
      ),
      'Error' => 
      array (
        'name' => 'Error',
        'value' => 
        array (
          'code' => '400',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 84,
            'startFilePos' => 1686,
            'endTokenPos' => 84,
            'endFilePos' => 1688,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Runtime errors
 */',
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Critical' => 
      array (
        'name' => 'Critical',
        'value' => 
        array (
          'code' => '500',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 95,
            'startFilePos' => 1835,
            'endTokenPos' => 95,
            'endFilePos' => 1837,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Critical conditions
 *
 * Example: Application component unavailable, unexpected exception.
 */',
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 24,
      ),
      'Alert' => 
      array (
        'name' => 'Alert',
        'value' => 
        array (
          'code' => '550',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 106,
            'startFilePos' => 2044,
            'endTokenPos' => 106,
            'endFilePos' => 2046,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Action must be taken immediately
 *
 * Example: Entire website down, database unavailable, etc.
 * This should trigger the SMS alerts and wake you up.
 */',
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 21,
      ),
      'Emergency' => 
      array (
        'name' => 'Emergency',
        'value' => 
        array (
          'code' => '600',
          'attributes' => 
          array (
            'startLine' => 82,
            'endLine' => 82,
            'startTokenPos' => 117,
            'startFilePos' => 2108,
            'endTokenPos' => 117,
            'endFilePos' => 2110,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Urgent alert.
 */',
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 25,
      ),
    ),
  ),
));
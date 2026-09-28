<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionFunction-deflate_add
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-dev-master@709e512-8.3',
   'data' => 
  array (
    'name' => 'deflate_add',
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
            'name' => 'DeflateContext',
            'isIdentifier' => false,
          ),
        ),
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '["8.0" => "DeflateContext"]',
                'attributes' => 
                array (
                  'startLine' => 26,
                  'endLine' => 26,
                  'startTokenPos' => 16,
                  'startFilePos' => 935,
                  'endTokenPos' => 22,
                  'endFilePos' => 961,
                ),
              ),
              'default' => 
              array (
                'code' => '"resource"',
                'attributes' => 
                array (
                  'startLine' => 26,
                  'endLine' => 26,
                  'startTokenPos' => 28,
                  'startFilePos' => 973,
                  'endTokenPos' => 28,
                  'endFilePos' => 982,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 26,
        'endLine' => 27,
        'startColumn' => 9,
        'endColumn' => 31,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'data' => 
      array (
        'name' => 'data',
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
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 9,
        'endColumn' => 20,
        'parameterIndex' => 1,
        'isOptional' => false,
      ),
      'flush_mode' => 
      array (
        'name' => 'flush_mode',
        'default' => 
        array (
          'code' => '\\ZLIB_SYNC_FLUSH',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 48,
            'startFilePos' => 1067,
            'endTokenPos' => 48,
            'endFilePos' => 1081,
          ),
        ),
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
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 9,
        'endColumn' => 41,
        'parameterIndex' => 2,
        'isOptional' => true,
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
              'name' => 'string',
              'isIdentifier' => true,
            ),
          ),
          1 => 
          array (
            'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
            'data' => 
            array (
              'name' => 'false',
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
 * Incrementally deflate data
 * @link https://php.net/manual/en/function.deflate-add.php
 * @param DeflateContext|resource $context <p>
 * A context created with <b>deflate_init()</b>.
 * </p>
 * @param string $data <p>
 * A chunk of data to compress.
 * </p>
 * @param int $flush_mode [optional] <p>
 * One of <b>ZLIB_BLOCK</b>, <b>ZLIB_NO_FLUSH</b>, <b>ZLIB_PARTIAL_FLUSH</b>,
 * <b>ZLIB_SYNC_FLUSH</b> (default), <b>ZLIB_FULL_FLUSH</b>,
 * <b>ZLIB_FINISH</b>. Normally you will want to set <b>ZLIB_NO_FLUSH</b> to
 * maximize compression, and <b>ZLIB_FINISH</b> to terminate with
 * the last chunk of data.
 * </p>
 * @return string|false <p>
 * Returns a chunk of compressed data, or <b>FALSE</b> on failure.
 * </p>
 * @since 7.0
 */',
    'startLine' => 25,
    'endLine' => 32,
    'startColumn' => 5,
    'endColumn' => 5,
    'couldThrow' => false,
    'isClosure' => false,
    'isGenerator' => false,
    'isVariadic' => false,
    'isStatic' => false,
    'namespace' => NULL,
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\InternalLocatedSource',
      'data' => 
      array (
        'name' => 'deflate_add',
        'filename' => 'phpstorm-stubs:zlib/zlib.stub',
        'extensionName' => 'zlib',
        'aliasName' => NULL,
      ),
    ),
  ),
));
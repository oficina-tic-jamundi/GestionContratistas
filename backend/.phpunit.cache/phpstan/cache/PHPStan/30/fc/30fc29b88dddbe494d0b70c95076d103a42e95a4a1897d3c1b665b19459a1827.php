<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionFunction-deflate_init
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-dev-master@709e512-8.3',
   'data' => 
  array (
    'name' => 'deflate_init',
    'parameters' => 
    array (
      'encoding' => 
      array (
        'name' => 'encoding',
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
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 9,
        'endColumn' => 21,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'options' => 
      array (
        'name' => 'options',
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 68,
            'startFilePos' => 1411,
            'endTokenPos' => 69,
            'endFilePos' => 1412,
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
                  'name' => 'object',
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
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '["8.3" => "array|object"]',
                'attributes' => 
                array (
                  'startLine' => 29,
                  'endLine' => 29,
                  'startTokenPos' => 44,
                  'startFilePos' => 1333,
                  'endTokenPos' => 50,
                  'endFilePos' => 1357,
                ),
              ),
              'default' => 
              array (
                'code' => '"array"',
                'attributes' => 
                array (
                  'startLine' => 29,
                  'endLine' => 29,
                  'startTokenPos' => 56,
                  'startFilePos' => 1369,
                  'endTokenPos' => 56,
                  'endFilePos' => 1375,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 29,
        'endLine' => 30,
        'startColumn' => 9,
        'endColumn' => 34,
        'parameterIndex' => 1,
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
              'name' => 'DeflateContext',
              'isIdentifier' => false,
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
      0 => 
      array (
        'name' => 'JetBrains\\PhpStorm\\Pure',
        'isRepeated' => false,
        'arguments' => 
        array (
        ),
      ),
      1 => 
      array (
        'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
        'isRepeated' => false,
        'arguments' => 
        array (
          0 => 
          array (
            'code' => '["8.0" => "DeflateContext|false"]',
            'attributes' => 
            array (
              'startLine' => 26,
              'endLine' => 26,
              'startTokenPos' => 15,
              'startFilePos' => 1158,
              'endTokenPos' => 21,
              'endFilePos' => 1190,
            ),
          ),
          'default' => 
          array (
            'code' => '"resource|false"',
            'attributes' => 
            array (
              'startLine' => 26,
              'endLine' => 26,
              'startTokenPos' => 27,
              'startFilePos' => 1202,
              'endTokenPos' => 27,
              'endFilePos' => 1217,
            ),
          ),
        ),
      ),
    ),
    'docComment' => '/**
 * Initialize an incremental deflate context
 * @link https://php.net/manual/en/function.deflate-init.php
 * @param int $encoding <p>
 * One of the <b>ZLIB_ENCODING_*</b> constants.
 * </p>
 * @param array|object $options <p>
 * An associative array which may contain the following elements:
 * <b>level</b>The compression level in range -1..9; defaults to -1.
 * <b>memory</b>The compression memory level in range 1..9; defaults to 8.
 * <b>window</b>The zlib window size (logarithmic) in range 8..15; defaults
 * to 15. <b>strategy</b>One of <b>ZLIB_FILTERED</b>, <b>ZLIB_HUFFMAN_ONLY</b>,
 * <b>ZLIB_RLE</b>, <b>ZLIB_FIXED</b> or <b>ZLIB_DEFAULT_STRATEGY</b> (the
 * default). <b>dictionary</b>A string or an array of strings of the preset
 * dictionary (default: no preset dictionary).</p>
 * @return resource|false|DeflateContext <p>
 * Returns a deflate context resource (zlib.deflate) on success, or
 * <b>FALSE</b> on failure.
 * </p>
 * @since 7.0
 */',
    'startLine' => 25,
    'endLine' => 33,
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
        'name' => 'deflate_init',
        'filename' => 'phpstorm-stubs:zlib/zlib.stub',
        'extensionName' => 'zlib',
        'aliasName' => NULL,
      ),
    ),
  ),
));
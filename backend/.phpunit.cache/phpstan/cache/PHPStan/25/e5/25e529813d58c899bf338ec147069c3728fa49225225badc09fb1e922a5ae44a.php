<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionFunction-mb_str_pad
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-dev-master@709e512-8.3',
   'data' => 
  array (
    'name' => 'mb_str_pad',
    'parameters' => 
    array (
      'string' => 
      array (
        'name' => 'string',
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
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 25,
        'endColumn' => 38,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'length' => 
      array (
        'name' => 'length',
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
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 41,
        'endColumn' => 51,
        'parameterIndex' => 1,
        'isOptional' => false,
      ),
      'pad_string' => 
      array (
        'name' => 'pad_string',
        'default' => 
        array (
          'code' => '" "',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 35,
            'startFilePos' => 1045,
            'endTokenPos' => 35,
            'endFilePos' => 1047,
          ),
        ),
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
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 54,
        'endColumn' => 77,
        'parameterIndex' => 2,
        'isOptional' => true,
      ),
      'pad_type' => 
      array (
        'name' => 'pad_type',
        'default' => 
        array (
          'code' => '\\STR_PAD_RIGHT',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 44,
            'startFilePos' => 1066,
            'endTokenPos' => 44,
            'endFilePos' => 1078,
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
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 80,
        'endColumn' => 108,
        'parameterIndex' => 3,
        'isOptional' => true,
      ),
      'encoding' => 
      array (
        'name' => 'encoding',
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 54,
            'startFilePos' => 1101,
            'endTokenPos' => 54,
            'endFilePos' => 1104,
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
                  'name' => 'string',
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
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 111,
        'endColumn' => 134,
        'parameterIndex' => 4,
        'isOptional' => true,
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
      0 => 
      array (
        'name' => 'JetBrains\\PhpStorm\\Pure',
        'isRepeated' => false,
        'arguments' => 
        array (
          0 => 
          array (
            'code' => '\\true',
            'attributes' => 
            array (
              'startLine' => 26,
              'endLine' => 26,
              'startTokenPos' => 11,
              'startFilePos' => 964,
              'endTokenPos' => 11,
              'endFilePos' => 967,
            ),
          ),
        ),
      ),
    ),
    'docComment' => '/**
 * Pad a multibyte string to a certain length with another multibyte string
 * @link https://php.net/manual/en/function.mb-str-pad.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $length <p>
 * The desired length of the padded string, measured in Unicode codepoints.
 * If the value is negative, less than, or equal to the length of the input string, no padding takes place.
 * </p>
 * @param string $pad_string [optional] <p>
 * The string to pad with. Default is a space.
 * </p>
 * @param int $pad_type [optional] <p>
 * Can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH.
 * </p>
 * @param string|null $encoding [optional] <p>
 * The character encoding. If omitted or null, the internal character encoding value will be used.
 * </p>
 * @return string The padded string.
 * @since 8.3
 */',
    'startLine' => 26,
    'endLine' => 29,
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
        'name' => 'mb_str_pad',
        'filename' => 'phpstorm-stubs:mbstring/mbstring.stub',
        'extensionName' => 'mbstring',
        'aliasName' => NULL,
      ),
    ),
  ),
));
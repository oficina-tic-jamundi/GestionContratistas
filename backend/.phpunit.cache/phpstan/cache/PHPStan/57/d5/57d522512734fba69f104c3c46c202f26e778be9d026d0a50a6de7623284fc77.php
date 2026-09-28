<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionFunction-imageflip
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-dev-master@709e512-8.3',
   'data' => 
  array (
    'name' => 'imageflip',
    'parameters' => 
    array (
      'image' => 
      array (
        'name' => 'image',
        'default' => NULL,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'GdImage',
            'isIdentifier' => false,
          ),
        ),
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 24,
        'endColumn' => 38,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'mode' => 
      array (
        'name' => 'mode',
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
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 41,
        'endColumn' => 49,
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
        'name' => 'bool',
        'isIdentifier' => true,
      ),
    ),
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
            'code' => '[\'8.5\' => \'true\']',
            'attributes' => 
            array (
              'startLine' => 44,
              'endLine' => 44,
              'startTokenPos' => 11,
              'startFilePos' => 1213,
              'endTokenPos' => 17,
              'endFilePos' => 1229,
            ),
          ),
          'default' => 
          array (
            'code' => '\'bool\'',
            'attributes' => 
            array (
              'startLine' => 44,
              'endLine' => 44,
              'startTokenPos' => 23,
              'startFilePos' => 1241,
              'endTokenPos' => 23,
              'endFilePos' => 1246,
            ),
          ),
        ),
      ),
    ),
    'docComment' => '/**
 * Flips an image using a given mode
 * @link https://secure.php.net/manual/en/function.imageflip.php
 * @param resource|GdImage $image <p>
 * An image resource, returned by one of the image creation functions, such as {@link https://secure.php.net/manual/en/function.imagecreatetruecolor.php imagecreatetruecolor()}.
 * </p>
 * @param int $mode <p>
 * Flip mode, this can be one of the <b>IMG_FLIP_*</b> constants:
 * </p>
 * <table>
 * <thead>
 * <tr>
 * <th>Constant</th>
 * <th>Meaning</th>
 * </tr>
 * </thead>
 * <tbody>
 * <tr>
 * <td><b>IMG_FLIP_HORIZONTAL</b></td>
 * <td>
 * Flips the image horizontally.
 * </td>
 * </tr>
 * <tr>
 * <td><b>IMG_FLIP_VERTICAL</b></td>
 * <td>
 * Flips the image vertically.
 * </td>
 * </tr>
 * <tr>
 * <td><b>IMG_FLIP_BOTH</b></td>
 * <td>
 * Flips the image both horizontally and vertically.
 * </td>
 * </tr>
 * </tbody>
 * </table>
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 5.5
 */',
    'startLine' => 44,
    'endLine' => 47,
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
        'name' => 'imageflip',
        'filename' => 'phpstorm-stubs:gd/gd.stub',
        'extensionName' => 'gd',
        'aliasName' => NULL,
      ),
    ),
  ),
));
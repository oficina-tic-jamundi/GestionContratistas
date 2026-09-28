<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionFunction-gd_info
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-dev-master@709e512-8.3',
   'data' => 
  array (
    'name' => 'gd_info',
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
        'name' => 'JetBrains\\PhpStorm\\ArrayShape',
        'isRepeated' => false,
        'arguments' => 
        array (
          0 => 
          array (
            'code' => '["GD Version" => "string", "FreeType Support" => "bool", "GIF Read Support" => "bool", "GIF Create Support" => "bool", "JPEG Support" => "bool", "PNG Support" => "bool", "WBMP Support" => "bool", "XPM Support" => "bool", "XBM Support" => "bool", "WebP Support" => "bool", "BMP Support" => "bool", "TGA Read Support" => "bool", "AVIF Support" => "bool", "JIS-mapped Japanese Font Support" => "bool"]',
            'attributes' => 
            array (
              'startLine' => 83,
              'endLine' => 83,
              'startTokenPos' => 15,
              'startFilePos' => 2446,
              'endTokenPos' => 112,
              'endFilePos' => 2843,
            ),
          ),
        ),
      ),
    ),
    'docComment' => '/**
 * Retrieve information about the currently installed GD library
 * @link https://php.net/manual/en/function.gd-info.php
 * @return array an associative array.
 * <p>
 * <table>
 * Elements of array returned by <b>gd_info</b>
 * <tr valign="top">
 * <td>Attribute</td>
 * <td>Meaning</td>
 * </tr>
 * <tr valign="top">
 * <td>GD Version</td>
 * <td>string value describing the installed
 * libgd version.</td>
 * </tr>
 * <tr valign="top">
 * <td>FreeType Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if FreeType Support is installed.</td>
 * </tr>
 * <tr valign="top">
 * <td>FreeType Linkage</td>
 * <td>string value describing the way in which
 * FreeType was linked. Expected values are: \'with freetype\',
 * \'with TTF library\', and \'with unknown library\'. This element will
 * only be defined if FreeType Support evaluated to
 * <b>TRUE</b>.</td>
 * </tr>
 * <tr valign="top">
 * <td>T1Lib Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if T1Lib support is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>GIF Read Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if support for reading GIF
 * images is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>GIF Create Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if support for creating GIF
 * images is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>JPEG Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if JPEG support is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>PNG Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if PNG support is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>WBMP Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if WBMP support is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>XBM Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if XBM support is included.</td>
 * </tr>
 * <tr valign="top">
 * <td>WebP Support</td>
 * <td>boolean value. <b>TRUE</b>
 * if WebP support is included.</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Previous to PHP 5.3.0, the JPEG Support attribute was named
 * JPG Support.
 * </p>
 */',
    'startLine' => 82,
    'endLine' => 86,
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
        'name' => 'gd_info',
        'filename' => 'phpstorm-stubs:gd/gd.stub',
        'extensionName' => 'gd',
        'aliasName' => NULL,
      ),
    ),
  ),
));
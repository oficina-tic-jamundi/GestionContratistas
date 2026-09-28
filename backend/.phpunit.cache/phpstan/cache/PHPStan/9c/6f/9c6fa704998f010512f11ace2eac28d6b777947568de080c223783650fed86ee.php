<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionFunction-exif_read_data
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-dev-master@709e512-8.3',
   'data' => 
  array (
    'name' => 'exif_read_data',
    'parameters' => 
    array (
      'file' => 
      array (
        'name' => 'file',
        'default' => NULL,
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 29,
        'endColumn' => 33,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'required_sections' => 
      array (
        'name' => 'required_sections',
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 78,
            'startTokenPos' => 22,
            'startFilePos' => 2880,
            'endTokenPos' => 22,
            'endFilePos' => 2883,
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
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 36,
        'endColumn' => 68,
        'parameterIndex' => 1,
        'isOptional' => true,
      ),
      'as_arrays' => 
      array (
        'name' => 'as_arrays',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 78,
            'startTokenPos' => 31,
            'startFilePos' => 2904,
            'endTokenPos' => 31,
            'endFilePos' => 2908,
          ),
        ),
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 71,
        'endColumn' => 93,
        'parameterIndex' => 2,
        'isOptional' => true,
      ),
      'read_thumbnail' => 
      array (
        'name' => 'read_thumbnail',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 78,
            'startTokenPos' => 40,
            'startFilePos' => 2934,
            'endTokenPos' => 40,
            'endFilePos' => 2938,
          ),
        ),
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 96,
        'endColumn' => 123,
        'parameterIndex' => 3,
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
              'name' => 'array',
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
 * Reads the EXIF headers from JPEG or TIFF
 * @link https://php.net/manual/en/function.exif-read-data.php
 * @param string $file <p>
 * The location of the image file. This cannot be an URL.
 * Since 7.2.0 this can either be a path to the file (stream wrappers are also supported as usual)
 * or a stream resource.
 * </p>
 * @param string|null $required_sections [optional] <p>
 * Is a comma separated list of sections that need to be present in file
 * to produce a result array. If none of the requested
 * sections could be found the return value is <b>FALSE</b>.
 * <tr valign="top">
 * <td>FILE</td>
 * <td>FileName, FileSize, FileDateTime, SectionsFound</td>
 * </tr>
 * <tr valign="top">
 * <td>COMPUTED</td>
 * <td>
 * html, Width, Height, IsColor, and more if available. Height and
 * Width are computed the same way <b>getimagesize</b>
 * does so their values must not be part of any header returned.
 * Also, html is a height/width text string to be used inside normal
 * HTML.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>ANY_TAG</td>
 * <td>Any information that has a Tag e.g. IFD0, EXIF, ...</td>
 * </tr>
 * <tr valign="top">
 * <td>IFD0</td>
 * <td>
 * All tagged data of IFD0. In normal imagefiles this contains
 * image size and so forth.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>THUMBNAIL</td>
 * <td>
 * A file is supposed to contain a thumbnail if it has a second IFD.
 * All tagged information about the embedded thumbnail is stored in
 * this section.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>COMMENT</td>
 * <td>Comment headers of JPEG images.</td>
 * </tr>
 * <tr valign="top">
 * <td>EXIF</td>
 * <td>
 * The EXIF section is a sub section of IFD0. It contains
 * more detailed information about an image. Most of these entries
 * are digital camera related.
 * </td>
 * </tr>
 * </p>
 * @param bool $as_arrays [optional] <p>
 * Specifies whether or not each section becomes an array. The
 * <i>sections</i> COMPUTED,
 * THUMBNAIL, and COMMENT
 * always become arrays as they may contain values whose names conflict
 * with other sections.
 * </p>
 * @param bool $read_thumbnail [optional] <p>
 * When set to <b>TRUE</b> the thumbnail itself is read. Otherwise, only the
 * tagged data is read.
 * </p>
 * @return array|false It returns an associative array where the array indexes are
 * the header names and the array values are the values associated with
 * those headers. If no data can be returned,
 * <b>exif_read_data</b> will return <b>FALSE</b>.
 */',
    'startLine' => 78,
    'endLine' => 80,
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
        'name' => 'exif_read_data',
        'filename' => 'phpstorm-stubs:exif/exif.stub',
        'extensionName' => 'exif',
        'aliasName' => NULL,
      ),
    ),
  ),
));
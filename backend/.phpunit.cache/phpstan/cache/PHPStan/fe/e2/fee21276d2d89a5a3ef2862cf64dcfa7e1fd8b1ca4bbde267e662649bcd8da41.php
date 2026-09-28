<?php declare(strict_types = 1);

// osfsl-C:/alcaldia/GECO_INFO/backend/vendor/composer/../psr/http-message/src/UploadedFileInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Psr\Http\Message\UploadedFileInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2823ae936f8f0030879fe12a507080d4eb91c6a534f57ff38e8f37561d78916c-8.3-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/vendor/composer/../psr/http-message/src/UploadedFileInterface.php',
      ),
    ),
    'namespace' => 'Psr\\Http\\Message',
    'name' => 'Psr\\Http\\Message\\UploadedFileInterface',
    'shortName' => 'UploadedFileInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Value object representing a file uploaded through an HTTP request.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 123,
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
      'getStream' => 
      array (
        'name' => 'getStream',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\StreamInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve a stream representing the uploaded file.
 *
 * This method MUST return a StreamInterface instance, representing the
 * uploaded file. The purpose of this method is to allow utilizing native PHP
 * stream functionality to manipulate the file upload, such as
 * stream_copy_to_stream() (though the result will need to be decorated in a
 * native PHP stream wrapper to work with such functions).
 *
 * If the moveTo() method has been called previously, this method MUST raise
 * an exception.
 *
 * @return StreamInterface Stream representation of the uploaded file.
 * @throws \\RuntimeException in cases when no stream is available or can be
 *     created.
 */',
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'aliasName' => NULL,
      ),
      'moveTo' => 
      array (
        'name' => 'moveTo',
        'parameters' => 
        array (
          'targetPath' => 
          array (
            'name' => 'targetPath',
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
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 28,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Move the uploaded file to a new location.
 *
 * Use this method as an alternative to move_uploaded_file(). This method is
 * guaranteed to work in both SAPI and non-SAPI environments.
 * Implementations must determine which environment they are in, and use the
 * appropriate method (move_uploaded_file(), rename(), or a stream
 * operation) to perform the operation.
 *
 * $targetPath may be an absolute path, or a relative path. If it is a
 * relative path, resolution should be the same as used by PHP\'s rename()
 * function.
 *
 * The original file or stream MUST be removed on completion.
 *
 * If this method is called more than once, any subsequent calls MUST raise
 * an exception.
 *
 * When used in an SAPI environment where $_FILES is populated, when writing
 * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
 * used to ensure permissions and upload status are verified correctly.
 *
 * If you wish to move to a stream, use getStream(), as SAPI operations
 * cannot guarantee writing to stream destinations.
 *
 * @see http://php.net/is_uploaded_file
 * @see http://php.net/move_uploaded_file
 * @param string $targetPath Path to which to move the uploaded file.
 * @throws \\InvalidArgumentException if the $targetPath specified is invalid.
 * @throws \\RuntimeException on any error during the move operation, or on
 *     the second or subsequent call to the method.
 */',
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'aliasName' => NULL,
      ),
      'getSize' => 
      array (
        'name' => 'getSize',
        'parameters' => 
        array (
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
                  'name' => 'int',
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
        'docComment' => '/**
 * Retrieve the file size.
 *
 * Implementations SHOULD return the value stored in the "size" key of
 * the file in the $_FILES array if available, as PHP calculates this based
 * on the actual size transmitted.
 *
 * @return int|null The file size in bytes or null if unknown.
 */',
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'aliasName' => NULL,
      ),
      'getError' => 
      array (
        'name' => 'getError',
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
 * Retrieve the error associated with the uploaded file.
 *
 * The return value MUST be one of PHP\'s UPLOAD_ERR_XXX constants.
 *
 * If the file was uploaded successfully, this method MUST return
 * UPLOAD_ERR_OK.
 *
 * Implementations SHOULD return the value stored in the "error" key of
 * the file in the $_FILES array.
 *
 * @see http://php.net/manual/en/features.file-upload.errors.php
 * @return int One of PHP\'s UPLOAD_ERR_XXX constants.
 */',
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'aliasName' => NULL,
      ),
      'getClientFilename' => 
      array (
        'name' => 'getClientFilename',
        'parameters' => 
        array (
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
        'docComment' => '/**
 * Retrieve the filename sent by the client.
 *
 * Do not trust the value returned by this method. A client could send
 * a malicious filename with the intention to corrupt or hack your
 * application.
 *
 * Implementations SHOULD return the value stored in the "name" key of
 * the file in the $_FILES array.
 *
 * @return string|null The filename sent by the client or null if none
 *     was provided.
 */',
        'startLine' => 107,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'aliasName' => NULL,
      ),
      'getClientMediaType' => 
      array (
        'name' => 'getClientMediaType',
        'parameters' => 
        array (
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
        'docComment' => '/**
 * Retrieve the media type sent by the client.
 *
 * Do not trust the value returned by this method. A client could send
 * a malicious media type with the intention to corrupt or hack your
 * application.
 *
 * Implementations SHOULD return the value stored in the "type" key of
 * the file in the $_FILES array.
 *
 * @return string|null The media type sent by the client or null if none
 *     was provided.
 */',
        'startLine' => 122,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UploadedFileInterface',
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
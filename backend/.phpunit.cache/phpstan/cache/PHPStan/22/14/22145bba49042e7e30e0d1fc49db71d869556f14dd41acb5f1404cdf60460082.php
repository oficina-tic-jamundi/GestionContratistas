<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Exceptions\SecurityCheckException.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Exceptions\SecurityCheckException
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-d2dd927eafc84b70c67922e48771230af3b484df659fa400bf3a9d94c3b55b50',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Exceptions/SecurityCheckException.php',
      ),
    ),
    'namespace' => 'Sigcon\\Exceptions',
    'name' => 'Sigcon\\Exceptions\\SecurityCheckException',
    'shortName' => 'SecurityCheckException',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Solicitud rechazada por un control de seguridad (CSRF, origen, cambio de contraseña pendiente).
 * Cada caso tiene su propio código para que el frontend reaccione correctamente.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 31,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Sigcon\\Exceptions\\AppException',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'CSRF_INVALID' => 
      array (
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'name' => 'CSRF_INVALID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'csrf_token_invalid\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 37,
            'startFilePos' => 344,
            'endTokenPos' => 37,
            'endFilePos' => 363,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'ORIGIN_NOT_ALLOWED' => 
      array (
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'name' => 'ORIGIN_NOT_ALLOWED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'origin_not_allowed\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 48,
            'startFilePos' => 404,
            'endTokenPos' => 48,
            'endFilePos' => 423,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 59,
      ),
      'PASSWORD_CHANGE_REQUIRED' => 
      array (
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'name' => 'PASSWORD_CHANGE_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'password_change_required\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 59,
            'startFilePos' => 470,
            'endTokenPos' => 59,
            'endFilePos' => 495,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 71,
      ),
    ),
    'immediateProperties' => 
    array (
      'securityCode' => 
      array (
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'name' => 'securityCode',
        'modifiers' => 132,
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
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 56,
        'endColumn' => 92,
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
          'publicMessage' => 
          array (
            'name' => 'publicMessage',
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
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 33,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'securityCode' => 
          array (
            'name' => 'securityCode',
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 56,
            'endColumn' => 92,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Exceptions',
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'currentClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'aliasName' => NULL,
      ),
      'statusCode' => 
      array (
        'name' => 'statusCode',
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
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Exceptions',
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'currentClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'aliasName' => NULL,
      ),
      'errorCode' => 
      array (
        'name' => 'errorCode',
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
        'startLine' => 27,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Exceptions',
        'declaringClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'implementingClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
        'currentClassName' => 'Sigcon\\Exceptions\\SecurityCheckException',
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
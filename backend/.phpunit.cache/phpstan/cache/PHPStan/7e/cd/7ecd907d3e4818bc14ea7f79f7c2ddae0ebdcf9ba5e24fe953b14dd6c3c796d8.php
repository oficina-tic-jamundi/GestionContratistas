<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\Activities\ContractTextReader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\Activities\ContractTextReader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-ededaee75c4020c7936ee03b924b814826ad892f2bd919bd4749029794fba9b6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\Activities\\ContractTextReader',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/Activities/ContractTextReader.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\Activities',
    'name' => 'Sigcon\\Services\\Activities\\ContractTextReader',
    'shortName' => 'ContractTextReader',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Texto de un contrato en PDF, leído en el servidor con una librería PHP (sin servicios
 * externos ni binarios del sistema: funciona en hosting compartido).
 *
 * Solo lee PDF con texto. Un PDF escaneado (imagen) no tiene texto: se informa y la
 * administración registra las obligaciones a mano. No hay OCR (ADR-022).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 47,
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
      'MIN_TEXT' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ContractTextReader',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ContractTextReader',
        'name' => 'MIN_TEXT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '200',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 55,
            'startFilePos' => 675,
            'endTokenPos' => 55,
            'endFilePos' => 677,
          ),
        ),
        'docComment' => '/** Menos texto que esto en todo el documento indica un escaneo sin capa de texto. */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'read' => 
      array (
        'name' => 'read',
        'parameters' => 
        array (
          'contents' => 
          array (
            'name' => 'contents',
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
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 26,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ContractTextReader',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ContractTextReader',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ContractTextReader',
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
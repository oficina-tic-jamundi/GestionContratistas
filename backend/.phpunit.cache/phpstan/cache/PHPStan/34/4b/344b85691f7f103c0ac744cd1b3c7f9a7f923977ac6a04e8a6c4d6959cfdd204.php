<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\Activities\ObligationExtractor.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\Activities\ObligationExtractor
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-408504ce3caa49b37a7d6fe348b7c68e43e423b9e073b2e117a97e5bb5aaeb44',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/Activities/ObligationExtractor.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\Activities',
    'name' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
    'shortName' => 'ObligationExtractor',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Propone las obligaciones de un contrato a partir de su texto (ADR-022).
 *
 * Es un análisis determinista, sin IA ni servicios externos (ADR-008): busca la sección de
 * obligaciones del contratista y separa su lista numerada. El resultado es una PROPUESTA: la
 * administración la revisa, corrige y confirma antes de que se registre cualquier obligación.
 *
 * Qué reconoce:
 * - Encabezados "OBLIGACIONES ESPECÍFICAS (DEL CONTRATISTA)" (preferido) u
 *   "OBLIGACIONES DEL CONTRATISTA" (si no hay específicas).
 * - Fin de la sección: obligaciones de la entidad/contratante/supervisor, las generales si
 *   vienen después, o el siguiente encabezado de cláusula (CLÁUSULA, VALOR, PLAZO, FORMA DE
 *   PAGO, SUPERVISIÓN, GARANTÍAS...).
 * - Ítems numerados "1." "1)" "1-" en orden consecutivo (así no confunde valores ni fechas),
 *   o con letras "a)" "b." si no hay números.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 208,
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
      'MAX_ITEMS' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'MAX_ITEMS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '60',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 33,
            'startFilePos' => 1040,
            'endTokenPos' => 33,
            'endFilePos' => 1041,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 32,
      ),
      'TITLE_MAX' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'TITLE_MAX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '180',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 44,
            'startFilePos' => 1073,
            'endTokenPos' => 44,
            'endFilePos' => 1075,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'SECTION_NOT_FOUND' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'SECTION_NOT_FOUND',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'section_not_found\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 57,
            'startFilePos' => 1180,
            'endTokenPos' => 57,
            'endFilePos' => 1198,
          ),
        ),
        'docComment' => '/** Resultado sin ítems cuando no se halla la sección. */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'NO_ITEMS' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'NO_ITEMS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'no_items\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 70,
            'startFilePos' => 1296,
            'endTokenPos' => 70,
            'endFilePos' => 1305,
          ),
        ),
        'docComment' => '/** La sección existe pero no tiene una lista reconocible. */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'GENERAL_SECTION' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'GENERAL_SECTION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'general_section\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 83,
            'startFilePos' => 1451,
            'endTokenPos' => 83,
            'endFilePos' => 1467,
          ),
        ),
        'docComment' => '/** Se usó la sección general porque no hay "específicas": puede incluir obligaciones genéricas. */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'TRUNCATED' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'TRUNCATED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'truncated\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 96,
            'startFilePos' => 1575,
            'endTokenPos' => 96,
            'endFilePos' => 1585,
          ),
        ),
        'docComment' => '/** Había más ítems que el máximo permitido; se cortó la lista. */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'SPECIFIC' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'SPECIFIC',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/OBLIGACIONES\\s+ESPEC[IÍ]FICAS(?:\\s+DEL\\s+CONTRATISTA)?/iu\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 107,
            'startFilePos' => 1618,
            'endTokenPos' => 107,
            'endFilePos' => 1678,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 91,
      ),
      'GENERAL' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'GENERAL',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/OBLIGACIONES\\s+(?:GENERALES\\s+)?DEL\\s+CONTRATISTA/iu\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 118,
            'startFilePos' => 1709,
            'endTokenPos' => 118,
            'endFilePos' => 1763,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 84,
      ),
      'STOP' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'STOP',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^\\s*(?:(?:CL[AÁ]USULA\\b[^\\n]{0,40})|(?:(?:PRIMERA|SEGUNDA|TERCERA|CUARTA|QUINTA|SEXTA|S[EÉ]PTIMA|OCTAVA|NOVENA|D[EÉ]CIMA|UND[EÉ]CIMA|DUOD[EÉ]CIMA|VIG[EÉ]SIMA)[\\s\\.\\-:–]))\' . \'|^\\s*(?:[\\d\\.\\-\\)\\s]{0,6})?(?:OBLIGACIONES\\s+(?:DE\\s+LA\\s+ENTIDAD|DEL\\s+CONTRATANTE|DEL\\s+MUNICIPIO|DE\\s+LA\\s+ALCALD[IÍ]A|DEL\\s+SUPERVISOR|GENERALES)\' . \'|VALOR\\s+(?:DEL\\s+CONTRATO|Y\\s+FORMA)|FORMA\\s+DE\\s+PAGO|PLAZO\\s+(?:DE\\s+EJECUCI[OÓ]N|DEL\\s+CONTRATO)|SUPERVISI[OÓ]N\\s*[:\\.\\-]|GARANT[IÍ]AS?\\s*[:\\.\\-]|DOMICILIO|INDEMNIDAD|PERFECCIONAMIENTO)/mu\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 43,
            'startTokenPos' => 131,
            'startFilePos' => 1878,
            'endTokenPos' => 139,
            'endFilePos' => 2429,
          ),
        ),
        'docComment' => '/** Encabezados que cierran la sección (se buscan al comienzo de una línea). */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 208,
      ),
      'INLINE_STOP' => 
      array (
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'name' => 'INLINE_STOP',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/[\\.;:]\\s+(?=CL[AÁ]USULA\\b|OBLIGACIONES\\s+(?:DE\\s+LA\\s+ENTIDAD|DEL\\s+CONTRATANTE|DEL\\s+MUNICIPIO|DEL\\s+SUPERVISOR|GENERALES))/u\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 152,
            'startFilePos' => 2568,
            'endTokenPos' => 152,
            'endFilePos' => 2697,
          ),
        ),
        'docComment' => '/** Encabezados en mayúscula dentro de la misma línea (PDF que entregan el texto sin saltos). */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 163,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'extract' => 
      array (
        'name' => 'extract',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 29,
            'endColumn' => 40,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array{items: list<array{title: string, description: ?string}>, warnings: list<string>}
 */',
        'startLine' => 51,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'aliasName' => NULL,
      ),
      'normalize' => 
      array (
        'name' => 'normalize',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 39,
            'endColumn' => 50,
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
        'startLine' => 100,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'aliasName' => NULL,
      ),
      'sectionStart' => 
      array (
        'name' => 'sectionStart',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'pattern' => 
          array (
            'name' => 'pattern',
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
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 56,
            'endColumn' => 70,
            'parameterIndex' => 1,
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
        'docComment' => '/** Posición (en caracteres) justo después del encabezado, o null. */',
        'startLine' => 114,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'aliasName' => NULL,
      ),
      'numbered' => 
      array (
        'name' => 'numbered',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
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
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 38,
            'endColumn' => 52,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ítems "1." "2)" "3-" consecutivos. Un número que no sigue la secuencia se toma como parte
 * del texto (ej. "1.000.000" o una fecha).
 *
 * @return list<string>
 */',
        'startLine' => 130,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'aliasName' => NULL,
      ),
      'lettered' => 
      array (
        'name' => 'lettered',
        'parameters' => 
        array (
          'section' => 
          array (
            'name' => 'section',
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
            'startLine' => 161,
            'endLine' => 161,
            'startColumn' => 38,
            'endColumn' => 52,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return list<string> */',
        'startLine' => 161,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'aliasName' => NULL,
      ),
      'clean' => 
      array (
        'name' => 'clean',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
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
            'startLine' => 181,
            'endLine' => 181,
            'startColumn' => 35,
            'endColumn' => 46,
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
        'startLine' => 181,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'aliasName' => NULL,
      ),
      'split' => 
      array (
        'name' => 'split',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 35,
            'endColumn' => 46,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Título corto (para listas y tarjetas) y la obligación completa como descripción.
 *
 * @return array{title: string, description: ?string}
 */',
        'startLine' => 196,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
        'currentClassName' => 'Sigcon\\Services\\Activities\\ObligationExtractor',
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
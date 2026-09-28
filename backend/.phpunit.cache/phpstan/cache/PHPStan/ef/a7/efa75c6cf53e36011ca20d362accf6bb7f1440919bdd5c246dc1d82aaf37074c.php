<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Services\Activities\WorkStatusResolver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Services\Activities\WorkStatusResolver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-a80b18dd6b1fc1c67c3b56d77e1864561e4da5263ddc959636ec93f1a70059f9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Services\\Activities\\WorkStatusResolver',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Services/Activities/WorkStatusResolver.php',
      ),
    ),
    'namespace' => 'Sigcon\\Services\\Activities',
    'name' => 'Sigcon\\Services\\Activities\\WorkStatusResolver',
    'shortName' => 'WorkStatusResolver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Deriva el estado de cada obligación, tarea y subtarea a partir de los informes (ADR-021).
 * No existe aprobación por subtarea: el supervisor aprueba u observa el informe completo, con
 * observaciones por obligación. Reglas:
 *
 * Elementos que reciben avance (hojas):
 * 1. Aprobada: está al 100 % y llegó al 100 % dentro de un período ya cubierto por un informe
 *    aprobado.
 * 2. Con observaciones: su obligación fue observada en la última revisión del último informe.
 * 3. En revisión: tuvo avances dentro del período del informe que está esperando revisión.
 * 4. Pendiente: en cualquier otro caso.
 *
 * Elementos con hijos: con observaciones si alguna hoja lo está; en revisión si alguna lo
 * está; aprobada si todas lo están; pendiente en otro caso. Una obligación observada en la
 * última revisión se muestra siempre "Con observaciones".
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 98,
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
      'resolve' => 
      array (
        'name' => 'resolve',
        'parameters' => 
        array (
          'activities' => 
          array (
            'name' => 'activities',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'observedObligationIds' => 
          array (
            'name' => 'observedObligationIds',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 9,
            'endColumn' => 36,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'inReviewIds' => 
          array (
            'name' => 'inReviewIds',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'approvedUntil' => 
          array (
            'name' => 'approvedUntil',
            'default' => NULL,
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
                      'name' => 'DateTimeImmutable',
                      'isIdentifier' => false,
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 9,
            'endColumn' => 41,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'lastCompletedAt' => 
          array (
            'name' => 'lastCompletedAt',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 9,
            'endColumn' => 30,
            'parameterIndex' => 4,
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
 * @param list<Activity> $activities       todos los elementos del contrato
 * @param list<int> $observedObligationIds obligaciones observadas en la última revisión
 * @param list<int> $inReviewIds            elementos con avances en el período en revisión
 * @param ?DateTimeImmutable $approvedUntil fin (exclusivo, UTC) del último período aprobado
 * @param array<int, DateTimeImmutable> $lastCompletedAt id => última vez que llegó al 100 %
 * @return array<int, WorkStatus> id de actividad => estado
 */',
        'startLine' => 37,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Services\\Activities',
        'declaringClassName' => 'Sigcon\\Services\\Activities\\WorkStatusResolver',
        'implementingClassName' => 'Sigcon\\Services\\Activities\\WorkStatusResolver',
        'currentClassName' => 'Sigcon\\Services\\Activities\\WorkStatusResolver',
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
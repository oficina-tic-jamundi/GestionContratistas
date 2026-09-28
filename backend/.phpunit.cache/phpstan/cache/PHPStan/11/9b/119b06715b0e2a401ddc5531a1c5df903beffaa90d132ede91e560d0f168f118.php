<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Repositories\HistoryFeedRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Repositories\HistoryFeedRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-2e3e37dc441411a1cfc9f7ebd3977de384391a43ecd94df62c9e056c6a01d592',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Repositories/HistoryFeedRepository.php',
      ),
    ),
    'namespace' => 'Sigcon\\Repositories',
    'name' => 'Sigcon\\Repositories\\HistoryFeedRepository',
    'shortName' => 'HistoryFeedRepository',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Historial cronológico (ADR-021): avances, evidencias y cambios de contratos, informes y
 * pagos. Aplica SIEMPRE el alcance del usuario (ContractScope):
 *
 * - administración (contracts.view_all): todos los contratos, con el nombre de quien actuó;
 * - supervisor (contracts.view_assigned): los contratos que supervisa, es decir lo suyo y lo
 *   de sus contratistas;
 * - contratista (contracts.view_own): sus contratos, lo que él hace y lo que el supervisor
 *   decide sobre sus informes.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 110,
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
      'KINDS' => 
      array (
        'declaringClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'name' => 'KINDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'progress\', \'evidence\', \'report\', \'payment\', \'contract\']',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 55,
            'startFilePos' => 797,
            'endTokenPos' => 69,
            'endFilePos' => 853,
          ),
        ),
        'docComment' => '/** Tipos de evento que se pueden filtrar. */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 83,
      ),
    ),
    'immediateProperties' => 
    array (
      'db' => 
      array (
        'declaringClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'name' => 'db',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Sigcon\\Database\\Database',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 33,
        'endColumn' => 61,
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
          'db' => 
          array (
            'name' => 'db',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Database\\Database',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 33,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Repositories',
        'declaringClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'currentClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'aliasName' => NULL,
      ),
      'feed' => 
      array (
        'name' => 'feed',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Repositories\\ContractScope',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 26,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'kind' => 
          array (
            'name' => 'kind',
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 48,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'page' => 
          array (
            'name' => 'page',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sigcon\\Http\\PageRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 63,
            'endColumn' => 79,
            'parameterIndex' => 2,
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
 * @return array{items: list<array{kind: string, occurred_at: DateTimeImmutable, summary: string, title: ?string, comment: ?string, from_progress: ?string, to_progress: ?string, ref_number: ?int, contract_number: string, contract_uuid: string, contractor: string, target_uuid: ?string, actor: ?string}>, total: int}
 */',
        'startLine' => 34,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Repositories',
        'declaringClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
        'currentClassName' => 'Sigcon\\Repositories\\HistoryFeedRepository',
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
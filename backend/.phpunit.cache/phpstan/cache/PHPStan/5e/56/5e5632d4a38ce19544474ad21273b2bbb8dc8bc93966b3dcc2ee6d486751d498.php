<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Repositories\MyHistoryRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Repositories\MyHistoryRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-0556d10d957401e76df5974a7a1762d14dca980b5041dde807c6677a4a0327b2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Repositories/MyHistoryRepository.php',
      ),
    ),
    'namespace' => 'Sigcon\\Repositories',
    'name' => 'Sigcon\\Repositories\\MyHistoryRepository',
    'shortName' => 'MyHistoryRepository',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Historial del contratista (ADR-021): avances, evidencias y cambios de sus contratos,
 * informes y pagos, en orden cronológico. Solo incluye contratos cuya cuenta vinculada es la
 * del usuario, y nunca contratos en borrador.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 92,
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
        'declaringClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'name' => 'KINDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'progress\', \'evidence\', \'report\', \'payment\', \'contract\']',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 55,
            'startFilePos' => 525,
            'endTokenPos' => 69,
            'endFilePos' => 581,
          ),
        ),
        'docComment' => '/** Tipos de evento que se pueden filtrar. */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 83,
      ),
      'OWN' => 
      array (
        'declaringClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'name' => 'OWN',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"JOIN contracts c ON c.id = %s JOIN contractors ct ON ct.id = c.contractor_id\\n        WHERE ct.user_id = :%s AND c.deleted_at IS NULL AND c.status <> \'draft\'"',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 23,
            'startTokenPos' => 80,
            'startFilePos' => 609,
            'endTokenPos' => 80,
            'endFilePos' => 766,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 81,
      ),
    ),
    'immediateProperties' => 
    array (
      'db' => 
      array (
        'declaringClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
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
        'startLine' => 25,
        'endLine' => 25,
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
            'startLine' => 25,
            'endLine' => 25,
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
        'startLine' => 25,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Repositories',
        'declaringClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'currentClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'aliasName' => NULL,
      ),
      'forUser' => 
      array (
        'name' => 'forUser',
        'parameters' => 
        array (
          'userId' => 
          array (
            'name' => 'userId',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 29,
            'endColumn' => 39,
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 42,
            'endColumn' => 54,
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 57,
            'endColumn' => 73,
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
 * @return array{items: list<array{kind: string, occurred_at: DateTimeImmutable, summary: string, title: ?string, comment: ?string, from_progress: ?string, to_progress: ?string, ref_number: ?int, contract_number: string, contract_uuid: string, target_uuid: ?string, actor: ?string}>, total: int}
 */',
        'startLine' => 32,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sigcon\\Repositories',
        'declaringClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'implementingClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
        'currentClassName' => 'Sigcon\\Repositories\\MyHistoryRepository',
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
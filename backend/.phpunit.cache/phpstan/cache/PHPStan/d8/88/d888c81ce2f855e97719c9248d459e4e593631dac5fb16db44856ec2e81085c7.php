<?php declare(strict_types = 1);

// odsl-C:\alcaldia\GECO_INFO\backend\src\Security\Permission.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sigcon\Security\Permission
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.3-9f425c2fe257572c0fd7c34161968eba07024028bf22267d19a27d9009f92fa0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sigcon\\Security\\Permission',
        'filename' => 'C:/alcaldia/GECO_INFO/backend/src/Security/Permission.php',
      ),
    ),
    'namespace' => 'Sigcon\\Security',
    'name' => 'Sigcon\\Security\\Permission',
    'shortName' => 'Permission',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => true,
    'isBackedEnum' => true,
    'modifiers' => 0,
    'docComment' => '/**
 * Catálogo central de permisos. Cada valor debe existir en la tabla `permissions`
 * (una prueba de integración verifica que coincidan). Los módulos nuevos agregan sus
 * casos aquí y una migración que los inserta.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 54,
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
      'name' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\Permission',
        'implementingClassName' => 'Sigcon\\Security\\Permission',
        'name' => 'name',
        'modifiers' => 2177,
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
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'value' => 
      array (
        'declaringClassName' => 'Sigcon\\Security\\Permission',
        'implementingClassName' => 'Sigcon\\Security\\Permission',
        'name' => 'value',
        'modifiers' => 2177,
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
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'cases' => 
      array (
        'name' => 'cases',
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
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\Permission',
        'implementingClassName' => 'Sigcon\\Security\\Permission',
        'currentClassName' => 'Sigcon\\Security\\Permission',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
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
            'name' => 'static',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\Permission',
        'implementingClassName' => 'Sigcon\\Security\\Permission',
        'currentClassName' => 'Sigcon\\Security\\Permission',
        'aliasName' => NULL,
      ),
      'tryFrom' => 
      array (
        'name' => 'tryFrom',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
                      'name' => 'int',
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
            'startLine' => NULL,
            'endLine' => NULL,
            'startColumn' => -1,
            'endColumn' => -1,
            'parameterIndex' => 0,
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
                  'name' => 'static',
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
        'docComment' => NULL,
        'startLine' => NULL,
        'endLine' => NULL,
        'startColumn' => -1,
        'endColumn' => -1,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sigcon\\Security',
        'declaringClassName' => 'Sigcon\\Security\\Permission',
        'implementingClassName' => 'Sigcon\\Security\\Permission',
        'currentClassName' => 'Sigcon\\Security\\Permission',
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
    'backingType' => 
    array (
      'name' => 'string',
      'isIdentifier' => true,
    ),
    'cases' => 
    array (
      'UsersView' => 
      array (
        'name' => 'UsersView',
        'value' => 
        array (
          'code' => '\'users.view\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 32,
            'startFilePos' => 337,
            'endTokenPos' => 32,
            'endFilePos' => 348,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'UsersCreate' => 
      array (
        'name' => 'UsersCreate',
        'value' => 
        array (
          'code' => '\'users.create\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 41,
            'startFilePos' => 374,
            'endTokenPos' => 41,
            'endFilePos' => 387,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'UsersUpdate' => 
      array (
        'name' => 'UsersUpdate',
        'value' => 
        array (
          'code' => '\'users.update\'',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 50,
            'startFilePos' => 413,
            'endTokenPos' => 50,
            'endFilePos' => 426,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'UsersDeactivate' => 
      array (
        'name' => 'UsersDeactivate',
        'value' => 
        array (
          'code' => '\'users.deactivate\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 59,
            'startFilePos' => 456,
            'endTokenPos' => 59,
            'endFilePos' => 473,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'UsersResetPassword' => 
      array (
        'name' => 'UsersResetPassword',
        'value' => 
        array (
          'code' => '\'users.reset_password\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 68,
            'startFilePos' => 506,
            'endTokenPos' => 68,
            'endFilePos' => 527,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'UsersAssignRoles' => 
      array (
        'name' => 'UsersAssignRoles',
        'value' => 
        array (
          'code' => '\'users.assign_roles\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 77,
            'startFilePos' => 558,
            'endTokenPos' => 77,
            'endFilePos' => 577,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'RolesView' => 
      array (
        'name' => 'RolesView',
        'value' => 
        array (
          'code' => '\'roles.view\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 86,
            'startFilePos' => 601,
            'endTokenPos' => 86,
            'endFilePos' => 612,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'RolesManage' => 
      array (
        'name' => 'RolesManage',
        'value' => 
        array (
          'code' => '\'roles.manage\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 95,
            'startFilePos' => 638,
            'endTokenPos' => 95,
            'endFilePos' => 651,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'AuditView' => 
      array (
        'name' => 'AuditView',
        'value' => 
        array (
          'code' => '\'audit.view\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 104,
            'startFilePos' => 675,
            'endTokenPos' => 104,
            'endFilePos' => 686,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'DepartmentsView' => 
      array (
        'name' => 'DepartmentsView',
        'value' => 
        array (
          'code' => '\'departments.view\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 113,
            'startFilePos' => 717,
            'endTokenPos' => 113,
            'endFilePos' => 734,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'DepartmentsManage' => 
      array (
        'name' => 'DepartmentsManage',
        'value' => 
        array (
          'code' => '\'departments.manage\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 122,
            'startFilePos' => 766,
            'endTokenPos' => 122,
            'endFilePos' => 785,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'ContractorsView' => 
      array (
        'name' => 'ContractorsView',
        'value' => 
        array (
          'code' => '\'contractors.view\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 131,
            'startFilePos' => 815,
            'endTokenPos' => 131,
            'endFilePos' => 832,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'ContractorsManage' => 
      array (
        'name' => 'ContractorsManage',
        'value' => 
        array (
          'code' => '\'contractors.manage\'',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 140,
            'startFilePos' => 864,
            'endTokenPos' => 140,
            'endFilePos' => 883,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'ContractsViewAll' => 
      array (
        'name' => 'ContractsViewAll',
        'value' => 
        array (
          'code' => '\'contracts.view_all\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 149,
            'startFilePos' => 914,
            'endTokenPos' => 149,
            'endFilePos' => 933,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'ContractsViewAssigned' => 
      array (
        'name' => 'ContractsViewAssigned',
        'value' => 
        array (
          'code' => '\'contracts.view_assigned\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 158,
            'startFilePos' => 969,
            'endTokenPos' => 158,
            'endFilePos' => 993,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 59,
      ),
      'ContractsViewOwn' => 
      array (
        'name' => 'ContractsViewOwn',
        'value' => 
        array (
          'code' => '\'contracts.view_own\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 167,
            'startFilePos' => 1024,
            'endTokenPos' => 167,
            'endFilePos' => 1043,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'ContractsManage' => 
      array (
        'name' => 'ContractsManage',
        'value' => 
        array (
          'code' => '\'contracts.manage\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 176,
            'startFilePos' => 1073,
            'endTokenPos' => 176,
            'endFilePos' => 1090,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'ContractsSupervise' => 
      array (
        'name' => 'ContractsSupervise',
        'value' => 
        array (
          'code' => '\'contracts.supervise\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 185,
            'startFilePos' => 1123,
            'endTokenPos' => 185,
            'endFilePos' => 1143,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 52,
      ),
      'ActivitiesExecute' => 
      array (
        'name' => 'ActivitiesExecute',
        'value' => 
        array (
          'code' => '\'activities.execute\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 194,
            'startFilePos' => 1176,
            'endTokenPos' => 194,
            'endFilePos' => 1195,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'ActivitiesPlan' => 
      array (
        'name' => 'ActivitiesPlan',
        'value' => 
        array (
          'code' => '\'activities.plan\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 203,
            'startFilePos' => 1224,
            'endTokenPos' => 203,
            'endFilePos' => 1240,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'ActivitiesProgress' => 
      array (
        'name' => 'ActivitiesProgress',
        'value' => 
        array (
          'code' => '\'activities.progress\'',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 212,
            'startFilePos' => 1273,
            'endTokenPos' => 212,
            'endFilePos' => 1293,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 52,
      ),
      'ReportsCreate' => 
      array (
        'name' => 'ReportsCreate',
        'value' => 
        array (
          'code' => '\'reports.create\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 221,
            'startFilePos' => 1322,
            'endTokenPos' => 221,
            'endFilePos' => 1337,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'ReportsReview' => 
      array (
        'name' => 'ReportsReview',
        'value' => 
        array (
          'code' => '\'reports.review\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 230,
            'startFilePos' => 1365,
            'endTokenPos' => 230,
            'endFilePos' => 1380,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'ReportsApprove' => 
      array (
        'name' => 'ReportsApprove',
        'value' => 
        array (
          'code' => '\'reports.approve\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 239,
            'startFilePos' => 1409,
            'endTokenPos' => 239,
            'endFilePos' => 1425,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'ReportsReopen' => 
      array (
        'name' => 'ReportsReopen',
        'value' => 
        array (
          'code' => '\'reports.reopen\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 248,
            'startFilePos' => 1453,
            'endTokenPos' => 248,
            'endFilePos' => 1468,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'EvidenceCapture' => 
      array (
        'name' => 'EvidenceCapture',
        'value' => 
        array (
          'code' => '\'evidence.capture\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 257,
            'startFilePos' => 1499,
            'endTokenPos' => 257,
            'endFilePos' => 1516,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'EvidenceViewOriginal' => 
      array (
        'name' => 'EvidenceViewOriginal',
        'value' => 
        array (
          'code' => '\'evidence.view_original\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 266,
            'startFilePos' => 1551,
            'endTokenPos' => 266,
            'endFilePos' => 1574,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'JobsManage' => 
      array (
        'name' => 'JobsManage',
        'value' => 
        array (
          'code' => '\'jobs.manage\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 275,
            'startFilePos' => 1600,
            'endTokenPos' => 275,
            'endFilePos' => 1612,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'PaymentsManage' => 
      array (
        'name' => 'PaymentsManage',
        'value' => 
        array (
          'code' => '\'payments.manage\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 284,
            'startFilePos' => 1642,
            'endTokenPos' => 284,
            'endFilePos' => 1658,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'PaymentsApprove' => 
      array (
        'name' => 'PaymentsApprove',
        'value' => 
        array (
          'code' => '\'payments.approve\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 293,
            'startFilePos' => 1688,
            'endTokenPos' => 293,
            'endFilePos' => 1705,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'PaymentsRegister' => 
      array (
        'name' => 'PaymentsRegister',
        'value' => 
        array (
          'code' => '\'payments.register\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 302,
            'startFilePos' => 1736,
            'endTokenPos' => 302,
            'endFilePos' => 1754,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'PaymentsConfigure' => 
      array (
        'name' => 'PaymentsConfigure',
        'value' => 
        array (
          'code' => '\'payments.configure\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 311,
            'startFilePos' => 1786,
            'endTokenPos' => 311,
            'endFilePos' => 1805,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'ExportsRun' => 
      array (
        'name' => 'ExportsRun',
        'value' => 
        array (
          'code' => '\'exports.run\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 320,
            'startFilePos' => 1831,
            'endTokenPos' => 320,
            'endFilePos' => 1843,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
    ),
  ),
));
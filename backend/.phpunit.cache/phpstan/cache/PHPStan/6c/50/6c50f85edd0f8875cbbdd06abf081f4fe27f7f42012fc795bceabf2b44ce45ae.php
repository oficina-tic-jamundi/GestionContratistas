<?php declare(strict_types = 1);

// ftm-C:\alcaldia\GECO_INFO\backend\src\Services\Auth\AuthService.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v6-2.3.5',
   'data' => 
  array (
    0 => 
    array (
      'b4db68125ec6f9382688c5ac06d2a587' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Auth',
         'uses' => 
        array (
          'database' => 'Sigcon\\Database\\Database',
          'authresult' => 'Sigcon\\DTOs\\AuthResult',
          'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
          'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'requestcontext' => 'Sigcon\\Logging\\RequestContext',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
          'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
        ),
         'className' => 'Sigcon\\Services\\Auth\\AuthService',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '99129aebe2b9a929e6fd993f8e8187db' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Auth',
         'uses' => 
        array (
          'database' => 'Sigcon\\Database\\Database',
          'authresult' => 'Sigcon\\DTOs\\AuthResult',
          'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
          'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'requestcontext' => 'Sigcon\\Logging\\RequestContext',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
          'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
        ),
         'className' => 'Sigcon\\Services\\Auth\\AuthService',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Auth',
           'uses' => 
          array (
            'database' => 'Sigcon\\Database\\Database',
            'authresult' => 'Sigcon\\DTOs\\AuthResult',
            'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
            'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'requestcontext' => 'Sigcon\\Logging\\RequestContext',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
            'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          ),
           'className' => 'Sigcon\\Services\\Auth\\AuthService',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '1483538c574a930d765434af95c89184' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Auth',
         'uses' => 
        array (
          'database' => 'Sigcon\\Database\\Database',
          'authresult' => 'Sigcon\\DTOs\\AuthResult',
          'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
          'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'requestcontext' => 'Sigcon\\Logging\\RequestContext',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
          'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
        ),
         'className' => 'Sigcon\\Services\\Auth\\AuthService',
         'functionName' => 'login',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Auth',
           'uses' => 
          array (
            'database' => 'Sigcon\\Database\\Database',
            'authresult' => 'Sigcon\\DTOs\\AuthResult',
            'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
            'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'requestcontext' => 'Sigcon\\Logging\\RequestContext',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
            'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          ),
           'className' => 'Sigcon\\Services\\Auth\\AuthService',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '7b882258c9a834bb359e2919cd81c92f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Auth',
         'uses' => 
        array (
          'database' => 'Sigcon\\Database\\Database',
          'authresult' => 'Sigcon\\DTOs\\AuthResult',
          'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
          'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'requestcontext' => 'Sigcon\\Logging\\RequestContext',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
          'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
        ),
         'className' => 'Sigcon\\Services\\Auth\\AuthService',
         'functionName' => 'accessOf',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Auth',
           'uses' => 
          array (
            'database' => 'Sigcon\\Database\\Database',
            'authresult' => 'Sigcon\\DTOs\\AuthResult',
            'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
            'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'requestcontext' => 'Sigcon\\Logging\\RequestContext',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
            'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          ),
           'className' => 'Sigcon\\Services\\Auth\\AuthService',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'c4f3f11c8d4d4f59442a3bad5a6febeb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Auth',
         'uses' => 
        array (
          'database' => 'Sigcon\\Database\\Database',
          'authresult' => 'Sigcon\\DTOs\\AuthResult',
          'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
          'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'requestcontext' => 'Sigcon\\Logging\\RequestContext',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
          'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
        ),
         'className' => 'Sigcon\\Services\\Auth\\AuthService',
         'functionName' => 'logout',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Auth',
           'uses' => 
          array (
            'database' => 'Sigcon\\Database\\Database',
            'authresult' => 'Sigcon\\DTOs\\AuthResult',
            'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
            'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'requestcontext' => 'Sigcon\\Logging\\RequestContext',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
            'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          ),
           'className' => 'Sigcon\\Services\\Auth\\AuthService',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '9d85154c3cc739c34fd0c94808ebd8c4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Auth',
         'uses' => 
        array (
          'database' => 'Sigcon\\Database\\Database',
          'authresult' => 'Sigcon\\DTOs\\AuthResult',
          'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
          'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'requestcontext' => 'Sigcon\\Logging\\RequestContext',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
          'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
        ),
         'className' => 'Sigcon\\Services\\Auth\\AuthService',
         'functionName' => 'changePassword',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Auth',
           'uses' => 
          array (
            'database' => 'Sigcon\\Database\\Database',
            'authresult' => 'Sigcon\\DTOs\\AuthResult',
            'authenticationexception' => 'Sigcon\\Exceptions\\AuthenticationException',
            'ratelimitexception' => 'Sigcon\\Exceptions\\RateLimitException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'requestcontext' => 'Sigcon\\Logging\\RequestContext',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'passwordhasher' => 'Sigcon\\Security\\PasswordHasher',
            'passwordpolicy' => 'Sigcon\\Security\\PasswordPolicy',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          ),
           'className' => 'Sigcon\\Services\\Auth\\AuthService',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
    ),
    1 => 
    array (
      'C:\\alcaldia\\GECO_INFO\\backend\\src\\Services\\Auth\\AuthService.php' => '285611394cf438589303387eff84ed0df20213dca1a1a4c72d02bc138d245823',
    ),
  ),
));
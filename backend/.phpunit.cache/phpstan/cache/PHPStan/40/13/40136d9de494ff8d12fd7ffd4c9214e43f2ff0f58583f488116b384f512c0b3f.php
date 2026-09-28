<?php declare(strict_types = 1);

// ftm-C:\alcaldia\GECO_INFO\backend\src\Services\Evidence\EvidenceService.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v6-2.3.5',
   'data' => 
  array (
    0 => 
    array (
      '45dfeb635f3672e94f91f7ff72079d26' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '776c658766de797ec3a01dbed5d27c50' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '8d713ae6e80d6a6e7013c02c048727e3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'forContract',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      'ab0edf96ddf44479607cfcee26ff404d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'capture',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '03fafbb30e11bf886a38e454ecb7574a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'image',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '6f4aa2db17c02c65f28caacf1c7fb823' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'withdraw',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      'b1d73db33cec73b1cc918f99bb4b8f50' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'watermarkLines',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      'f5780ec606d75405c4149485863a2d55' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'locationStatus',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '980ef59c2d3015eaa6f9633d7c8d6591' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'canCapture',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '813152ae01ef974da87fcf90b028d85e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'canWithdraw',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      'f2d7bc114f09feff6f83eb0fa738abe8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'isExecutor',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '52fbea71b1e9004eaf72462e501bfb4b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'findActivity',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '36365a34bc644111a73a8a6618ea4914' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'contractOf',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      'b084d888aa818c7c075e00861b33f171' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'find',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '7e17093967f1d8442c274e324cc8177e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'contractOfEvidence',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      '07ac184c4db54da1cc96e20bf8a5a561' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Evidence',
         'uses' => 
        array (
          'datetimeimmutable' => 'DateTimeImmutable',
          'datetimezone' => 'DateTimeZone',
          'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
          'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
          'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
          'database' => 'Sigcon\\Database\\Database',
          'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'datetimes' => 'Sigcon\\Helpers\\DateTimes',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'activity' => 'Sigcon\\Models\\Activity',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'evidence' => 'Sigcon\\Models\\Evidence',
          'locationstatus' => 'Sigcon\\Models\\LocationStatus',
          'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
          'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
          'filestorage' => 'Sigcon\\Storage\\FileStorage',
        ),
         'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
         'functionName' => 'decimal',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Evidence',
           'uses' => 
          array (
            'datetimeimmutable' => 'DateTimeImmutable',
            'datetimezone' => 'DateTimeZone',
            'streaminterface' => 'Psr\\Http\\Message\\StreamInterface',
            'uploadedfileinterface' => 'Psr\\Http\\Message\\UploadedFileInterface',
            'evidencesettings' => 'Sigcon\\Config\\EvidenceSettings',
            'database' => 'Sigcon\\Database\\Database',
            'authorizationexception' => 'Sigcon\\Exceptions\\AuthorizationException',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'datetimes' => 'Sigcon\\Helpers\\DateTimes',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'activity' => 'Sigcon\\Models\\Activity',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'evidence' => 'Sigcon\\Models\\Evidence',
            'locationstatus' => 'Sigcon\\Models\\LocationStatus',
            'activityrepository' => 'Sigcon\\Repositories\\ActivityRepository',
            'evidencerepository' => 'Sigcon\\Repositories\\EvidenceRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractservice' => 'Sigcon\\Services\\Contracts\\ContractService',
            'filestorage' => 'Sigcon\\Storage\\FileStorage',
          ),
           'className' => 'Sigcon\\Services\\Evidence\\EvidenceService',
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
      'C:\\alcaldia\\GECO_INFO\\backend\\src\\Services\\Evidence\\EvidenceService.php' => '5c87bef91549ba3005931012b6eab9a0b4b3703ca577d18d4201b883794e7d68',
    ),
  ),
));
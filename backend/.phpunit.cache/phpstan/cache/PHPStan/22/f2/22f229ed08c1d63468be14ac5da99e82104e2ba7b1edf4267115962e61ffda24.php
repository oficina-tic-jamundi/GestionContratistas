<?php declare(strict_types = 1);

// ftm-C:\alcaldia\GECO_INFO\backend\src\Services\Contracts\ContractService.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v6-2.3.5',
   'data' => 
  array (
    0 => 
    array (
      '4290b7c96418312bcd0badb481500918' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '87a0a26b13077729adc746cb420bdc70' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => '__construct',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'e770aa99946a2ff3c6f028a1a27a0e2c' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'list',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '47f33c83bd80704a8fd8a8c2e382afa1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'get',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '7748242082c17d98887798a5424cfbc4' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'getById',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '3417f6af8c5d83aedb75a13d6e6dd739' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'isOwnContract',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'fba2c8e0075c9f41aae5180fccf1a0dc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'reload',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'd1c51be1984c47d41f2b5f3d5909eb61' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'history',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'f63c9aa0c1a8ca2d37b46f412c5d0e98' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'supervisorCandidates',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '3d46230636c1c51e91462bfffba9a1b5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'create',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'fb1106d61ffc21da6b7843b96153ddbc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'update',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '252ed4f9c6302cb53ce84eb195ef84b0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'transition',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '8e295e2be39be45918852f844f544185' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'changeSupervisor',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '9e626c8eb58e6bc1db5e977d15011542' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'deleteDraft',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'f79a026f0485a70d5d251a0a780f54a9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'resolveRefs',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'fae482d7681d47af9066a4556e335c85' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'eligibleSupervisor',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'b81cb27a7147b8a86dada0407566afc3' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'assertReadyToActivate',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '51f6fd0531fa22d26242762d2715546f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'diff',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      '0e3dbf22c3d44c72ecea45fb35fa43ef' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Sigcon\\Services\\Contracts',
         'uses' => 
        array (
          'pdoexception' => 'PDOException',
          'database' => 'Sigcon\\Database\\Database',
          'contractdata' => 'Sigcon\\DTOs\\ContractData',
          'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
          'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
          'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
          'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
          'clock' => 'Sigcon\\Helpers\\Clock',
          'uuid' => 'Sigcon\\Helpers\\Uuid',
          'page' => 'Sigcon\\Http\\Page',
          'pagerequest' => 'Sigcon\\Http\\PageRequest',
          'contract' => 'Sigcon\\Models\\Contract',
          'contractstatus' => 'Sigcon\\Models\\ContractStatus',
          'historyentry' => 'Sigcon\\Models\\HistoryEntry',
          'user' => 'Sigcon\\Models\\User',
          'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
          'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
          'contractscope' => 'Sigcon\\Repositories\\ContractScope',
          'userrepository' => 'Sigcon\\Repositories\\UserRepository',
          'authcontext' => 'Sigcon\\Security\\AuthContext',
          'permission' => 'Sigcon\\Security\\Permission',
          'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
          'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
          'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
          'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
          'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
        ),
         'className' => 'Sigcon\\Services\\Contracts\\ContractService',
         'functionName' => 'numberTaken',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Sigcon\\Services\\Contracts',
           'uses' => 
          array (
            'pdoexception' => 'PDOException',
            'database' => 'Sigcon\\Database\\Database',
            'contractdata' => 'Sigcon\\DTOs\\ContractData',
            'contractfilters' => 'Sigcon\\DTOs\\ContractFilters',
            'businessruleexception' => 'Sigcon\\Exceptions\\BusinessRuleException',
            'notfoundexception' => 'Sigcon\\Exceptions\\NotFoundException',
            'validationexception' => 'Sigcon\\Exceptions\\ValidationException',
            'clock' => 'Sigcon\\Helpers\\Clock',
            'uuid' => 'Sigcon\\Helpers\\Uuid',
            'page' => 'Sigcon\\Http\\Page',
            'pagerequest' => 'Sigcon\\Http\\PageRequest',
            'contract' => 'Sigcon\\Models\\Contract',
            'contractstatus' => 'Sigcon\\Models\\ContractStatus',
            'historyentry' => 'Sigcon\\Models\\HistoryEntry',
            'user' => 'Sigcon\\Models\\User',
            'businesshistoryrepository' => 'Sigcon\\Repositories\\BusinessHistoryRepository',
            'contractrepository' => 'Sigcon\\Repositories\\ContractRepository',
            'contractscope' => 'Sigcon\\Repositories\\ContractScope',
            'userrepository' => 'Sigcon\\Repositories\\UserRepository',
            'authcontext' => 'Sigcon\\Security\\AuthContext',
            'permission' => 'Sigcon\\Security\\Permission',
            'auditaction' => 'Sigcon\\Services\\Audit\\AuditAction',
            'auditlogger' => 'Sigcon\\Services\\Audit\\AuditLogger',
            'contractorservice' => 'Sigcon\\Services\\Contractors\\ContractorService',
            'departmentservice' => 'Sigcon\\Services\\Departments\\DepartmentService',
            'historyrecorder' => 'Sigcon\\Services\\History\\HistoryRecorder',
          ),
           'className' => 'Sigcon\\Services\\Contracts\\ContractService',
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
      'C:\\alcaldia\\GECO_INFO\\backend\\src\\Services\\Contracts\\ContractService.php' => 'dd290ea54d9bdbc569b8c2dc5f165f2087c65afe03b2690d87636ed7218f2920',
    ),
  ),
));
<?php

declare(strict_types=1);

namespace Sigcon\Services\Activities;

use Sigcon\Exceptions\AuthorizationException;
use Sigcon\Exceptions\BusinessRuleException;
use Sigcon\Models\Contract;
use Sigcon\Models\ContractStatus;
use Sigcon\Models\Document;
use Sigcon\Repositories\ActivityRepository;
use Sigcon\Repositories\DocumentRepository;
use Sigcon\Security\AuthContext;
use Sigcon\Security\Permission;
use Sigcon\Services\Contracts\ContractService;
use Sigcon\Storage\FileStorage;

/**
 * Obligaciones propuestas a partir del contrato firmado (ADR-022).
 *
 * Solo lee: toma el último "Contrato firmado" vigente del contrato, extrae su texto y propone
 * la lista. No registra nada; la administración confirma con `ActivityService::createObligations`.
 */
final class ObligationImportService
{
    public function __construct(
        private readonly ContractService $contracts,
        private readonly DocumentRepository $documents,
        private readonly ActivityRepository $activities,
        private readonly FileStorage $storage,
        private readonly ContractTextReader $reader,
        private readonly ObligationExtractor $extractor,
        private readonly AuthContext $auth,
    ) {
    }

    /**
     * @return array{
     *     contract: Contract,
     *     document: Document,
     *     items: list<array{title: string, description: ?string}>,
     *     warnings: list<string>,
     *     existing: int
     * }
     */
    public function suggest(string $contractUuid): array
    {
        $contract = $this->contracts->get($contractUuid);
        if (!$this->auth->can(Permission::ContractsManage)) {
            throw new AuthorizationException('No tiene permiso para registrar las obligaciones de este contrato.');
        }
        if ($contract->status !== ContractStatus::Draft) {
            throw new BusinessRuleException('Las obligaciones solo se toman del contrato mientras está en borrador.');
        }

        $document = $this->signedContract($contract)
            ?? throw new BusinessRuleException('Cargue primero el contrato firmado en PDF para leer sus obligaciones.');

        $result = $this->extractor->extract(
            $this->reader->read($this->storage->readStream($document->storagePath)->getContents())
        );

        return [
            'contract' => $contract,
            'document' => $document,
            'items' => $result['items'],
            'warnings' => $result['warnings'],
            'existing' => count(array_filter(
                $this->activities->forContract($contract->id),
                static fn ($a) => $a->parentId === null,
            )),
        ];
    }

    private function signedContract(Contract $contract): ?Document
    {
        foreach ($this->documents->forEntity('contract', $contract->id) as $document) {
            if ($document->status === 'active' && $document->typeCode === 'signed_contract' && $document->mimeType === 'application/pdf') {
                return $document; // vienen del más reciente al más antiguo
            }
        }

        return null;
    }
}

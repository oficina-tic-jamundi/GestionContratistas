<?php

declare(strict_types=1);

namespace Sigcon\Services\Reports\Compose;

use Sigcon\Exceptions\ValidationException;
use Sigcon\Helpers\Uuid;
use Sigcon\Validators\Validator;

/** Datos para crear el acta o el informe desde la actividad (ADR-023). */
final class ComposedDocumentData
{
    public const MAX_PHOTOS = 20;

    /**
     * @param array<string, string> $fields valores ya validados, por código de campo
     * @param list<array{uuid: string, caption: string}> $photos
     */
    public function __construct(
        public readonly ReportTemplate $template,
        public readonly string $activityUuid,
        public readonly array $fields,
        public readonly array $photos,
    ) {
    }

    /** @param array<mixed>|object|null $body */
    public static function fromBody(array|object|null $body): self
    {
        $data = is_array($body) ? $body : [];
        $v = Validator::fromBody($body);

        $template = $v->enum('template', ReportTemplate::class);
        $activity = $v->uuid('activity');

        $rawFields = is_array($data['fields'] ?? null) ? $data['fields'] : [];
        $fields = [];
        if ($template instanceof ReportTemplate) {
            $fv = Validator::fromBody($rawFields);
            foreach ($template->fields() as $code => $spec) {
                $value = match ($spec['kind']) {
                    'date' => $fv->date($code, $spec['required'])?->format('Y-m-d'),
                    'time' => self::time($fv, $rawFields, $code),
                    default => $fv->string($code, $spec['max'], max(1, $spec['min']), $spec['required']),
                };
                if ($value !== null && $value !== '') {
                    $fields[$code] = $value;
                }
            }
            if (!$fv->isValid()) {
                try {
                    $fv->throwIfInvalid();
                } catch (ValidationException $e) {
                    foreach ($e->errors() as $error) {
                        $v->add('fields.' . ($error['field'] ?? ''), $error['code'], $error['message']);
                    }
                }
            }
        }

        $photos = [];
        $rawPhotos = $data['photos'] ?? [];
        if (!is_array($rawPhotos) || !array_is_list($rawPhotos) || count($rawPhotos) > self::MAX_PHOTOS) {
            $v->add('photos', 'invalid', sprintf('Envíe como máximo %d fotos.', self::MAX_PHOTOS));
        } else {
            foreach ($rawPhotos as $i => $photo) {
                $uuid = is_array($photo) && is_string($photo['uuid'] ?? null) ? $photo['uuid'] : '';
                $caption = is_array($photo) && is_string($photo['caption'] ?? null) ? trim($photo['caption']) : '';
                if (!Uuid::isValid($uuid)) {
                    $v->add("photos.{$i}.uuid", 'invalid', 'Foto no válida.');
                } elseif (mb_strlen($caption) > 200) {
                    $v->add("photos.{$i}.caption", 'too_long', 'La descripción de la foto admite hasta 200 caracteres.');
                } else {
                    $photos[] = ['uuid' => $uuid, 'caption' => $caption];
                }
            }
        }

        $v->throwIfInvalid();

        return new self(
            $template instanceof ReportTemplate ? $template : throw new \LogicException('Formato validado ausente.'),
            Validator::present($activity),
            $fields,
            $photos,
        );
    }

    /** @param array<mixed> $raw */
    private static function time(Validator $v, array $raw, string $code): ?string
    {
        $value = $raw[$code] ?? null;
        if ($value === null || $value === '') {
            return null;
        }
        if (!is_string($value) || preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value) !== 1) {
            $v->add($code, 'invalid', 'Indique la hora como HH:MM (24 horas).');

            return null;
        }

        return $value;
    }

    public function mainText(): string
    {
        return $this->fields[$this->template->mainField()] ?? '';
    }
}

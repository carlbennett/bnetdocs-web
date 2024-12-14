<?php

namespace BNETDocs\Models\Document;

class Delete extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_NOT_FOUND = 'NOT_FOUND';

    public ?\BNETDocs\Libraries\Document $document = null;
    public ?int $id = null;
    public ?string $title = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'document' => $this->document,
            'id' => $this->id,
            'title' => $this->title,
        ]);
    }
}

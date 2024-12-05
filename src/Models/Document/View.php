<?php

namespace BNETDocs\Models\Document;

class View extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public ?array $comments = null;
    public ?\BNETDocs\Libraries\Document $document = null;
    public ?int $document_id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'comments' => $this->comments,
            'document' => $this->document,
            'document_id' => $this->document_id,
        ]);
    }
}

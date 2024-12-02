<?php

namespace BNETDocs\Models\Document;

class View extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?array $comments = null;
    public ?\BNETDocs\Libraries\Document $document = null;
    public ?int $document_id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'comments' => $this->comments,
            'document' => $this->document,
            'document_id' => $this->document_id,
        ]);
    }
}

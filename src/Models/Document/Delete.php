<?php

namespace BNETDocs\Models\Document;

class Delete extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_FOUND = 'NOT_FOUND';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

    public bool $acl_allowed = false;
    public ?\BNETDocs\Libraries\Document $document = null;
    public ?int $id = null;
    public ?string $title = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'document' => $this->document,
            'id' => $this->id,
            'title' => $this->title,
        ]);
    }
}

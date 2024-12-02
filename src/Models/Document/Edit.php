<?php

namespace BNETDocs\Models\Document;

class Edit extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_EMPTY_CONTENT = 'EMPTY_CONTENT';
    public const ERROR_EMPTY_TITLE = 'EMPTY_TITLE';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_FOUND = 'NOT_FOUND';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

    public bool $acl_allowed = false;
    public ?string $brief = null;
    public ?string $category = null;
    public ?array $comments = null;
    public ?string $content = null;
    public ?\BNETDocs\Libraries\Document $document = null;
    public ?int $document_id = null;
    public ?bool $markdown = null;
    public ?bool $published = null;
    public ?string $title = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'brief' => $this->brief,
            'category' => $this->category,
            'comments' => $this->comments,
            'content' => $this->content,
            'document' => $this->document,
            'document_id' => $this->document_id,
            'markdown' => $this->markdown,
            'published' => $this->published,
            'title' => $this->title,
        ]);
    }
}

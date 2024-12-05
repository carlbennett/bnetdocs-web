<?php

namespace BNETDocs\Models\Document;

class Create extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_EMPTY_CONTENT = 'EMPTY_CONTENT';
    public const ERROR_EMPTY_TITLE = 'EMPTY_TITLE';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

    public ?string $brief = null;
    public ?string $content = null;
    public bool $markdown = true;
    public ?string $title = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'brief' => $this->brief,
            'content' => $this->content,
            'markdown' => $this->markdown,
            'title' => $this->title,
        ]);
    }
}

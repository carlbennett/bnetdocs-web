<?php

namespace BNETDocs\Models\Comment;

class Delete extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_FOUND = 'NOT_FOUND';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

    public bool $acl_allowed = false;
    public ?\BNETDocs\Libraries\Comment $comment = null;
    public ?string $content = null;
    public ?int $id = null;
    public ?int $parent_id = null;
    public ?int $parent_type = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'comment' => $this->comment,
            'content' => $this->content,
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent_type' => $this->parent_type,
        ]);
    }
}

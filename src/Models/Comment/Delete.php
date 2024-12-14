<?php

namespace BNETDocs\Models\Comment;

class Delete extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_NOT_FOUND = 'NOT_FOUND';

    public ?\BNETDocs\Libraries\Comment $comment = null;
    public ?string $content = null;
    public ?int $id = null;
    public ?int $parent_id = null;
    public ?int $parent_type = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'comment' => $this->comment,
            'content' => $this->content,
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent_type' => $this->parent_type,
        ]);
    }
}

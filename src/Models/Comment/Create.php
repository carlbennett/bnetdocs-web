<?php

namespace BNETDocs\Models\Comment;

class Create extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_EMPTY_CONTENT = 'EMPTY_CONTENT';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

    public ?\BNETDocs\Libraries\Comment $comment = null;
    public ?string $origin = null;
    public ?array $response = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'comment' => $this->comment,
            'origin' => $this->origin,
            'response' => $this->response,
        ]);
    }
}

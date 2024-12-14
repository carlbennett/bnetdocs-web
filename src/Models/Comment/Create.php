<?php

namespace BNETDocs\Models\Comment;

class Create extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_EMPTY_CONTENT = 'EMPTY_CONTENT';

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

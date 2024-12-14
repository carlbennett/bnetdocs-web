<?php

namespace BNETDocs\Models\User;

class Delete extends \BNETDocs\Models\Core\HttpForm implements \JsonSerializable
{
    public const ERROR_NOT_FOUND = 'NOT_FOUND';

    public bool $deleted = false;
    public ?int $target_id = null;
    public ?\BNETDocs\Libraries\User\User $target_user = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'deleted' => $this->deleted,
            'target_id' => $this->target_id,
            'target_user' => $this->target_user,
        ]);
    }
}

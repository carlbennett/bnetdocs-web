<?php

namespace BNETDocs\Models\User;

class ChangePassword extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $error_extra = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['error_extra' => $this->error_extra]);
    }
}

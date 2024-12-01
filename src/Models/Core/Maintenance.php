<?php

namespace BNETDocs\Models\Core;

class Maintenance extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $message = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['message' => $this->message]);
    }
}

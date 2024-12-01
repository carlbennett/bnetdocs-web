<?php

namespace BNETDocs\Models\Core;

class Status extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public array $status = [];

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['status' => $this->status]);
    }
}

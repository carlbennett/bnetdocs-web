<?php

namespace BNETDocs\Models\Core;

class Redirect extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $location = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['location' => $this->location]);
    }
}

<?php

namespace BNETDocs\Models\Core;

class Robotstxt extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?\stdClass $rules = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['rules' => $this->rules]);
    }
}

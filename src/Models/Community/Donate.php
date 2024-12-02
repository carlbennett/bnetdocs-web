<?php

namespace BNETDocs\Models\Community;

class Donate extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public mixed $donations = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['donations' => $this->donations]);
    }
}

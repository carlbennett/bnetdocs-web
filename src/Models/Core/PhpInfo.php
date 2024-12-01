<?php

namespace BNETDocs\Models\Core;

class PhpInfo extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $phpinfo = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['phpinfo' => $this->phpinfo]);
    }
}

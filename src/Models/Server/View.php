<?php

namespace BNETDocs\Models\Server;

class View extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?\BNETDocs\Libraries\Server\Server $server = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['server' => $this->server]);
    }
}

<?php

namespace BNETDocs\Models\Server;

class Index extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?array $server_types = null;
    public ?array $servers = null;
    public ?array $status_bitmasks = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'server_types' => $this->server_types,
            'servers' => $this->servers,
            'status_bitmasks' => $this->status_bitmasks,
        ]);
    }
}

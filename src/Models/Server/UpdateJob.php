<?php

namespace BNETDocs\Models\Server;

class UpdateJob extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public int $old_status_bitmask = 0;
    public ?\BNETDocs\Libraries\Server\Server $server = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'old_status_bitmask' => $this->old_status_bitmask,
            'server' => $this->server,
        ]);
    }
}

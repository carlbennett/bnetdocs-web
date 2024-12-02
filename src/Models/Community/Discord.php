<?php

namespace BNETDocs\Models\Community;

class Discord extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $discord_server_id = null;
    public ?string $discord_url = null;
    public bool $enabled = false;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'discord_server_id' => $this->discord_server_id,
            'discord_url' => $this->discord_url,
            'enabled' => $this->enabled,
        ]);
    }
}

<?php

namespace BNETDocs\Models\EventLog;

class View extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?\BNETDocs\Libraries\EventLog\Event $event = null;
    public ?int $id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'event' => $this->event,
            'id' => $this->id,
        ]);
    }
}

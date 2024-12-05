<?php

namespace BNETDocs\Models\EventLog;

class View extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public ?\BNETDocs\Libraries\EventLog\Event $event = null;
    public ?int $id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'event' => $this->event,
            'id' => $this->id,
        ]);
    }
}

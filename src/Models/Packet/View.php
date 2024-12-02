<?php

namespace BNETDocs\Models\Packet;

class View extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?array $comments = null;
    public ?\BNETDocs\Libraries\Packet\Packet $packet = null;
    public ?int $packet_id = null;
    public ?array $used_by = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'comments' => $this->comments,
            'packet' => $this->packet,
            'packet_id' => $this->packet_id,
            'used_by' => $this->used_by,
        ]);
    }
}

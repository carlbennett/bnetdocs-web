<?php

namespace BNETDocs\Models\Packet;

class Delete extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?int $id = null;
    public ?\BNETDocs\Libraries\Packet\Packet $packet = null;
    public ?string $title = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'id' => $this->id,
            'packet' => $this->packet,
            'title' => $this->title,
        ]);
    }
}

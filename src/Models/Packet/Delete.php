<?php

namespace BNETDocs\Models\Packet;

class Delete extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_FOUND = 'NOT_FOUND';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

    public ?string $label = null;
    public ?\BNETDocs\Libraries\Packet\Packet $packet = null;
    public ?int $packet_id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'label' => $this->label,
            'packet' => $this->packet,
            'packet_id' => $this->packet_id,
        ]);
    }
}

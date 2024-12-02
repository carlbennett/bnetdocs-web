<?php

namespace BNETDocs\Models\Packet;

class Index extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?array $application_layers = null;
    public array|string|null $order = null;
    public array|false $packets = false;
    public array $pktapplayer = [];
    public ?\DateTimeInterface $timestamp = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'application_layers' => $this->application_layers,
            'order' => $this->order,
            'packets' => $this->packets,
            'pktapplayer' => $this->pktapplayer,
            'timestamp' => $this->timestamp,
        ]);
    }
}

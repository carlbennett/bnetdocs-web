<?php

namespace BNETDocs\Models\Packet;

class Index extends \BNETDocs\Models\ActiveUser
{
  public $application_layers;
  public array|string|null $order = null;
  public array|false $packets = false;
  public array $pktapplayer = [];
  public ?\DateTimeInterface $timestamp = null;
}

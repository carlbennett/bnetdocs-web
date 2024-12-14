<?php

namespace BNETDocs\Models\Packet;

class Form extends \BNETDocs\Models\Core\HttpForm implements \JsonSerializable
{
  // possible values for $error:
  public const ERROR_NOT_FOUND = 'NOT_FOUND';
  public const ERROR_OUTOFBOUNDS_APPLICATION_LAYER_ID = 'OUTOFBOUNDS_APPLICATION_LAYER_ID';
  public const ERROR_OUTOFBOUNDS_BRIEF = 'OUTOFBOUNDS_BRIEF';
  public const ERROR_OUTOFBOUNDS_DIRECTION = 'OUTOFBOUNDS_DIRECTION';
  public const ERROR_OUTOFBOUNDS_FORMAT = 'OUTOFBOUNDS_FORMAT';
  public const ERROR_OUTOFBOUNDS_ID = 'OUTOFBOUNDS_ID';
  public const ERROR_OUTOFBOUNDS_NAME = 'OUTOFBOUNDS_NAME';
  public const ERROR_OUTOFBOUNDS_PACKET_ID = 'OUTOFBOUNDS_PACKET_ID';
  public const ERROR_OUTOFBOUNDS_REMARKS = 'OUTOFBOUNDS_REMARKS';
  public const ERROR_OUTOFBOUNDS_TRANSPORT_LAYER_ID = 'OUTOFBOUNDS_TRANSPORT_LAYER_ID';
  public const ERROR_OUTOFBOUNDS_USED_BY = 'OUTOFBOUNDS_USED_BY';

  public ?array $comments = null;
  public ?\BNETDocs\Libraries\Packet\Packet $packet = null;
  public ?array $products = null;

  /**
   * Implements the JSON serialization function from the JsonSerializable interface.
   */
  public function jsonSerialize(): mixed
  {
    return \array_merge(parent::jsonSerialize(), [
      'comments' => $this->comments,
      'packet' => $this->packet,
      'products' => $this->products,
    ]);
  }
}

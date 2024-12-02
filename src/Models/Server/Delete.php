<?php

namespace BNETDocs\Models\Server;

class Delete extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
  public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
  public const ERROR_INTERNAL = 'INTERNAL_ERROR';
  public const ERROR_NOT_FOUND = 'NOT_FOUND';
  public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

  public ?\BNETDocs\Libraries\Server\Server $server = null;

  public function jsonSerialize(): mixed
  {
    return \array_merge(parent::jsonSerialize(), ['server' => $this->server]);
  }
}

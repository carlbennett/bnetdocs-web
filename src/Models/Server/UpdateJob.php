<?php

namespace BNETDocs\Models\Server;

class UpdateJob extends \BNETDocs\Models\ActiveUser
{
  public int $old_status_bitmask = 0;
  public ?\BNETDocs\Libraries\Server\Server $server = null;
}
